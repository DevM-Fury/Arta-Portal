<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    // JSON or form-data
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents("php://input"), true) ?? [];
        $id = $data['id'] ?? null;
        $division_name = trim($data['division_name'] ?? '');
        $division_description = trim($data['division_description'] ?? '');
        $division_status = $data['division_status'] ?? '';
        $office_id = isset($data['office_id']) && $data['office_id'] !== '' ? (int)$data['office_id'] : null;
    } else {
        $id = $_POST['id'] ?? null;
        $division_name = trim($_POST['division_name'] ?? '');
        $division_description = trim($_POST['division_description'] ?? '');
        $division_status = $_POST['division_status'] ?? '';
        $office_id = isset($_POST['office_id']) && $_POST['office_id'] !== '' ? (int)$_POST['office_id'] : null;
    }

    if (empty($id)) respond_system(400, "Division ID is required");
    if (empty($division_name) || empty($division_status)) respond_system(400, "Division name and status are required");

    // existence
    $check = $pdo_system->prepare("SELECT id FROM divisions WHERE id = :id");
    $check->execute([':id' => $id]);
    if (!$check->fetch()) respond_system(404, "Division not found");

    // if office_id provided, ensure it exists
    if ($office_id !== null) {
        $s = $pdo_system->prepare("SELECT id FROM offices WHERE id = :id");
        $s->execute([':id' => $office_id]);
        if (!$s->fetch()) respond_system(404, "Assigned office not found");
    }

    // Update
    $sql = "UPDATE divisions
            SET division_name = :name,
                division_description = :desc,
                division_status = :status,
                office_id = :office_id,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':name' => $division_name,
        ':desc' => $division_description,
        ':status' => $division_status,
        ':office_id' => $office_id,
        ':id' => $id
    ]);

    respond_system(200, "Division updated successfully");
} catch (Exception $e) {
    respond_system(500, "Failed to update division", ['error' => $e->getMessage()]);
}