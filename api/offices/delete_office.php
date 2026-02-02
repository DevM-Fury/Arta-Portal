<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    // JSON or form-data
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
    } else {
        $id = $_POST['id'] ?? null;
    }

    if (empty($id)) {
        respond_system(400, "Office ID is required.");
    }

    // Check existence
    $stmt = $pdo_system->prepare("SELECT id FROM offices WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        respond_system(404, "Office not found.");
    }

    // Delete
    $stmt = $pdo_system->prepare("DELETE FROM offices WHERE id = :id");
    $stmt->execute([':id' => $id]);

    respond_system(200, "Office deleted successfully");

} catch (Exception $e) {
    respond_system(500, "Failed to delete office", ["error" => $e->getMessage()]);
}