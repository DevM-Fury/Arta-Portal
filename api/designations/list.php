<?php
require_once __DIR__ . '/../../includes/config_register.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

try {
    $designations = get_active_designations($pdo_register);
    respond_register(200, "Designations fetched successfully", [
        'designations' => $designations,
        'total' => count($designations)
    ]);
} catch (Exception $e) {
    respond_register(500, "Failed to fetch designations");
}
?>