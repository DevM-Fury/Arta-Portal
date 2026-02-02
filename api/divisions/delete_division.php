<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents("php://input"), true) ?? [];
        $id = $data['id'] ?? null;
    } else {
        $id = $_POST['id'] ?? null;
    }

    if (empty($id)) respond_system(400, "Division ID is required");

    $stmt = $pdo_system->prepare("SELECT id FROM divisions WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) respond_system(404, "Division not found");

    $del = $pdo_system->prepare("DELETE FROM divisions WHERE id = :id");
    $del->execute([':id' => $id]);

    respond_system(200, "Division deleted successfully");
} catch (Exception $e) {
    respond_system(500, "Failed to delete division", ['error' => $e->getMessage()]);
}