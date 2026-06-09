<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../includes/config_system.php';
require_once __DIR__ . '/../../includes/log_helper.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond_system(405, "Invalid request method");
    }

    $systemName = "";
    $description = "";
    $domainUrl = "";
    $status = "Active";
    $logoPath = null;

    // ✅ Case 1: JSON body
    if (isset($_SERVER["CONTENT_TYPE"]) && stripos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input) {
            respond_system(400, "Invalid or missing JSON body");
        }
        $systemName = trim($input['system_name'] ?? '');
        $description = trim($input['description'] ?? '');
        $domainUrl   = trim($input['domain_url'] ?? '');
        $status      = $input['status'] ?? 'Active';
    } 
    // ✅ Case 2: Form-data
    else {
        $systemName = trim($_POST['system_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $domainUrl   = trim($_POST['domain_url'] ?? '');
        $status      = $_POST['status'] ?? 'Active';

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $fileName = time() . "_" . basename($_FILES['logo']['name']);
            $targetPath = SYSTEM_UPLOAD_DIR . $fileName;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                $logoPath = "uploads/" . $fileName;
            } else {
                respond_system(500, "Failed to upload logo");
            }
        }
    }

    if (empty($systemName)) {
        respond_system(400, "System name is required");
    }

    // ✅ Duplication Check (by system_name OR domain_url)
    $check = $pdo_system->prepare("SELECT COUNT(*) FROM systems WHERE system_name = :name OR domain_url = :domain");
    $check->execute([':name' => $systemName, ':domain' => $domainUrl]);
    if ($check->fetchColumn() > 0) {
        respond_system(409, "System already exists with the same name or domain");
    }

    // ✅ Insert sa DB
    $sql = "INSERT INTO systems (system_name, description, logo_path, status, domain_url) 
            VALUES (:name, :description, :logo, :status, :domain)";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':name' => $systemName,
        ':description' => $description,
        ':logo' => $logoPath,
        ':status' => $status,
        ':domain' => $domainUrl
    ]);

    $newSystemId = $pdo_system->lastInsertId();

    if (!empty($_SESSION['user']['portal_id'])) {
        log_transaction(
            $pdo_system,
            $_SESSION['user']['portal_id'],
            'CREATE_SYSTEM',
            'systems',
            $newSystemId,
            'Created a new external system entry.',
            [
                'system_name' => $systemName,
                'status' => $status,
                'domain_url' => $domainUrl,
            ],
            $_SESSION['user']['role'] ?? null
        );
    }

    respond_system(200, "System created successfully", [
        "id" => $newSystemId,
        "system_name" => $systemName,
        "description" => $description,
        "status" => $status,
        "domain_url" => $domainUrl,
        "logo_path" => $logoPath
    ]);

} catch (Exception $e) {
    respond_system(500, "Failed to create system", ["error" => $e->getMessage()]);
}