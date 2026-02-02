<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond_system(405, "Invalid request method");
    }

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = trim($_POST['designation_name'] ?? '');
    $desc = trim($_POST['designation_description'] ?? '');
    $status = $_POST['designation_status'] ?? '';

    if ($id <= 0) respond_system(400, "Designation ID is required");
    if ($name === '') respond_system(400, "Designation name is required");

    // ensure exists
    $check = $pdo_system->prepare("SELECT id FROM designations WHERE id = :id");
    $check->execute([':id' => $id]);
    if (!$check->fetch()) respond_system(404, "Designation not found");

    $sql = "UPDATE designations
            SET designation_name = :name,
                designation_description = :desc,
                designation_status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':desc' => $desc,
        ':status' => $status,
        ':id' => $id
    ]);

    respond_system(200, "Designation updated successfully");

} catch (Exception $e) {
    respond_system(500, "Failed to update designation", ['error' => $e->getMessage()]);
}