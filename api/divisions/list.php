<?php
require_once __DIR__ . '/../../includes/config_register.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

try {
    $divisions = get_active_divisions($pdo_register);
    respond_register(200, "Divisions fetched successfully", [
        'divisions' => $divisions,
        'total' => count($divisions)
    ]);
} catch (Exception $e) {
    respond_register(500, "Failed to fetch divisions");
}
?>