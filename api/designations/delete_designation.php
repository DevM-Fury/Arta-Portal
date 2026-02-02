<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond_system(405, "Invalid request method");
    }

    // accept form-data or urlencoded body
    $id = $_POST['id'] ?? null;
    if ($id === null) {
        // try JSON body fallback
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
    }
    $id = (int)$id;

    if ($id <= 0) respond_system(400, "Designation ID is required");

    $check = $pdo_system->prepare("SELECT id FROM designations WHERE id = :id");
    $check->execute([':id' => $id]);
    if (!$check->fetch()) respond_system(404, "Designation not found");

    $del = $pdo_system->prepare("DELETE FROM designations WHERE id = :id");
    $del->execute([':id' => $id]);

    respond_system(200, "Designation deleted successfully");

} catch (Exception $e) {
    respond_system(500, "Failed to delete designation", ['error' => $e->getMessage()]);
}