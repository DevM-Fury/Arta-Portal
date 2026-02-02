<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond_system(405, "Invalid request method");
    }

    // parse JSON or form-data
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $input = json_decode(file_get_contents("php://input"), true) ?? [];
        $division_name = trim($input['division_name'] ?? '');
        $division_description = trim($input['division_description'] ?? '');
        $division_status = $input['division_status'] ?? 'Active';
        $office_id = isset($input['office_id']) && $input['office_id'] !== '' ? (int)$input['office_id'] : null;
    } else {
        $division_name = trim($_POST['division_name'] ?? '');
        $division_description = trim($_POST['division_description'] ?? '');
        $division_status = $_POST['division_status'] ?? 'Active';
        $office_id = isset($_POST['office_id']) && $_POST['office_id'] !== '' ? (int)$_POST['office_id'] : null;
    }

    if (empty($division_name)) {
        respond_system(400, "Division name is required");
    }

    // duplication check
    $chk = $pdo_system->prepare("SELECT COUNT(*) FROM divisions WHERE division_name = :name");
    $chk->execute([':name' => $division_name]);
    if ($chk->fetchColumn() > 0) {
        respond_system(409, "Division already exists with the same name");
    }

    // if office_id provided, ensure it exists
    if ($office_id !== null) {
        $s = $pdo_system->prepare("SELECT id FROM offices WHERE id = :id");
        $s->execute([':id' => $office_id]);
        if (!$s->fetch()) respond_system(404, "Assigned office not found");
    }

    // Insert (Postgres RETURNING)
    $sql = "INSERT INTO divisions (division_name, division_description, division_status, office_id)
            VALUES (:name, :desc, :status, :office_id)
            RETURNING id, created_at, updated_at";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':name' => $division_name,
        ':desc' => $division_description,
        ':status' => $division_status,
        ':office_id' => $office_id
    ]);
    $inserted = $stmt->fetch(PDO::FETCH_ASSOC);

    respond_system(200, "Division created successfully", [
        'division' => array_merge($inserted, [
            'division_name' => $division_name,
            'division_description' => $division_description,
            'division_status' => $division_status,
            'office_id' => $office_id
        ])
    ]);
} catch (Exception $e) {
    respond_system(500, "Failed to create division", ['error' => $e->getMessage()]);
}