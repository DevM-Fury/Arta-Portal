<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . "/../../includes/config_system.php";
require_once __DIR__ . '/../../includes/log_helper.php';
require_once __DIR__ . '/../../includes/sso_helper.php';

$newLogoPath = null;

function system_logo_upload_error_message($errorCode) {
    switch ((int) $errorCode) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'Logo upload failed. The file is too large.';
        case UPLOAD_ERR_PARTIAL:
            return 'Logo upload failed. The file was only partially uploaded.';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Logo upload failed. Missing temporary upload folder.';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Logo upload failed. Unable to write the uploaded file.';
        case UPLOAD_ERR_EXTENSION:
            return 'Logo upload failed. A PHP extension blocked the upload.';
        default:
            return 'Logo upload failed.';
    }
}

function store_system_logo_upload($fieldName = 'logo') {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        respond_system(400, system_logo_upload_error_message($_FILES[$fieldName]['error']));
    }

    $maxSize = 5 * 1024 * 1024;
    if ((int) $_FILES[$fieldName]['size'] > $maxSize) {
        respond_system(400, 'Logo file is too large. Maximum size is 5MB.');
    }

    $tmpName = $_FILES[$fieldName]['tmp_name'];
    $detectedMime = null;

    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $detectedMime = finfo_file($finfo, $tmpName);
            finfo_close($finfo);
        }
    }

    $mimeType = $detectedMime ?: ($_FILES[$fieldName]['type'] ?? '');
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    if (!isset($allowedTypes[$mimeType])) {
        respond_system(400, 'Invalid logo file type. Please upload JPG, PNG, GIF, or WEBP.');
    }

    $uploadDir = SYSTEM_UPLOAD_DIR . 'systems/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
        respond_system(500, 'Failed to prepare logo upload folder.');
    }

    $originalName = pathinfo($_FILES[$fieldName]['name'], PATHINFO_FILENAME);
    $safeName = preg_replace('/[^A-Za-z0-9_-]+/', '_', $originalName);
    $safeName = trim((string) $safeName, '_');
    if ($safeName === '') {
        $safeName = 'logo';
    }

    $filename = uniqid('system_logo_', true) . '_' . substr($safeName, 0, 80) . '.' . $allowedTypes[$mimeType];
    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($tmpName, $targetPath)) {
        respond_system(500, 'Failed to upload logo.');
    }

    return 'uploads/systems/' . $filename;
}

function delete_system_uploaded_logo($logoPath) {
    $relativePath = ltrim((string) $logoPath, '/');
    if ($relativePath === '' || strpos($relativePath, 'uploads/') !== 0) {
        return;
    }

    $fullPath = realpath(__DIR__ . '/../../' . $relativePath);
    $uploadsRoot = realpath(SYSTEM_UPLOAD_DIR);

    if ($fullPath && $uploadsRoot && strpos($fullPath, $uploadsRoot . DIRECTORY_SEPARATOR) === 0 && is_file($fullPath)) {
        unlink($fullPath);
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond_system(405, "Invalid request method");
    }

    $isJson = isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;

    if ($isJson) {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!is_array($data)) {
            respond_system(400, "Invalid or missing JSON body");
        }

        $idRaw = $data['id'] ?? '';
        $systemName = trim($data['system_name'] ?? '');
        $domainUrl = trim($data['domain_url'] ?? '');
        $description = trim($data['description'] ?? '');
        $status = trim($data['status'] ?? '');
    } else {
        $idRaw = $_POST['id'] ?? '';
        $systemName = trim($_POST['system_name'] ?? '');
        $domainUrl = trim($_POST['domain_url'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = trim($_POST['status'] ?? '');
    }

    $idRaw = trim((string) $idRaw);
    if ($idRaw === '' || !ctype_digit($idRaw)) {
        respond_system(400, "System ID is required.");
    }

    if ($systemName === '' || $status === '') {
        respond_system(400, "System name and status are required.");
    }

    $statusMap = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];
    $statusKey = strtolower(trim((string) $status));
    if (!isset($statusMap[$statusKey])) {
        respond_system(400, "Status must be Active or Inactive");
    }
    $status = $statusMap[$statusKey];

    if ($status === 'Active' && $domainUrl === '') {
        respond_system(400, "Domain URL is required for active systems");
    }

    if ($domainUrl !== '' && (filter_var($domainUrl, FILTER_VALIDATE_URL) === false || !sso_is_safe_url($domainUrl))) {
        respond_system(400, "Domain URL must be a valid HTTP or HTTPS URL");
    }

    $id = (int) $idRaw;

    $existingStmt = $pdo_system->prepare("SELECT id, logo_path FROM systems WHERE id = :id");
    $existingStmt->execute([':id' => $id]);
    $existingSystem = $existingStmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingSystem) {
        respond_system(404, "System not found.");
    }

    $duplicateSql = "SELECT COUNT(*) FROM systems WHERE id <> :id AND (system_name = :system_name";
    $duplicateParams = [
        ':id' => $id,
        ':system_name' => $systemName,
    ];

    if ($domainUrl !== '') {
        $duplicateSql .= " OR domain_url = :domain_url";
        $duplicateParams[':domain_url'] = $domainUrl;
    }

    $duplicateSql .= ")";
    $duplicateStmt = $pdo_system->prepare($duplicateSql);
    $duplicateStmt->execute($duplicateParams);

    if ($duplicateStmt->fetchColumn() > 0) {
        respond_system(409, "System already exists with the same name or domain");
    }

    $logoPath = $existingSystem['logo_path'] ?? null;
    if (!$isJson) {
        $newLogoPath = store_system_logo_upload('logo');
        if ($newLogoPath !== null) {
            $logoPath = $newLogoPath;
        }
    }

    $sql = "UPDATE systems
            SET system_name = :system_name,
                description = :description,
                logo_path = :logo_path,
                status = :status,
                domain_url = :domain_url,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";

    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ":system_name" => $systemName,
        ":description" => $description,
        ":logo_path" => $logoPath,
        ":status" => $status,
        ":domain_url" => $domainUrl,
        ":id" => $id,
    ]);

    if ($newLogoPath !== null && !empty($existingSystem['logo_path']) && $existingSystem['logo_path'] !== $newLogoPath) {
        delete_system_uploaded_logo($existingSystem['logo_path']);
    }

    if (!empty($_SESSION['user']['portal_id'])) {
        log_transaction(
            $pdo_system,
            $_SESSION['user']['portal_id'],
            'UPDATE_SYSTEM',
            'systems',
            $id,
            'Updated a system record.',
            [
                'system_name' => $systemName,
                'status' => $status,
                'domain_url' => $domainUrl,
            ],
            $_SESSION['user']['role'] ?? null
        );
    }

    respond_system(200, "System updated successfully", [
        "id" => $id,
        "system_name" => $systemName,
        "description" => $description,
        "status" => $status,
        "domain_url" => $domainUrl,
        "logo_path" => $logoPath,
    ]);

} catch (Exception $e) {
    if ($newLogoPath !== null) {
        delete_system_uploaded_logo($newLogoPath);
    }

    error_log('systems update_system failed: ' . $e->getMessage());
    respond_system(500, "Unable to update system right now.");
}
