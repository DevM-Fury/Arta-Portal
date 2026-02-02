<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $id = $data['id'] ?? null;
    } else {
        $id = $_POST['id'] ?? null;
    }

    if (empty($id)) respond_system(400, "Unit ID is required");

    $s = $pdo_system->prepare("SELECT id FROM units WHERE id = :id");
    $s->execute([':id' => $id]);
    if (!$s->fetch()) respond_system(404, "Unit not found");

    $del = $pdo_system->prepare("DELETE FROM units WHERE id = :id");
    $del->execute([':id' => $id]);

    respond_system(200, "Unit deleted successfully");
} catch (Exception $e) {
    respond_system(500, "Failed to delete unit", ['error' => $e->getMessage()]);
}