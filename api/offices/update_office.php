<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    // JSON or form-data
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
        $office_name = trim($data['office_name'] ?? '');
        $description = trim($data['description'] ?? '');
        $status = $data['status'] ?? '';
    } else {
        $id = $_POST['id'] ?? null;
        $office_name = trim($_POST['office_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? '';
    }

    if (empty($id)) {
        respond_system(400, "Office ID is required.");
    }
    if (empty($office_name) || empty($status)) {
        respond_system(400, "Office name and status are required.");
    }

    // Optionally check existence
    $check = $pdo_system->prepare("SELECT id FROM offices WHERE id = :id");
    $check->execute([':id' => $id]);
    if (!$check->fetch()) {
        respond_system(404, "Office not found");
    }

    // Update
    $sql = "UPDATE offices
            SET office_name = :office_name,
                description = :description,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':office_name' => $office_name,
        ':description' => $description,
        ':status' => $status,
        ':id' => $id
    ]);

    respond_system(200, "Office updated successfully");

} catch (Exception $e) {
    respond_system(500, "Failed to update office", ["error" => $e->getMessage()]);
}