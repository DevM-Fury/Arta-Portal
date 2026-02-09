<?php
require_once __DIR__ . '/../../includes/config_register.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

try {
    $offices = get_active_offices($pdo_register);
    respond_register(200, 'Offices fetched successfully', [
        'offices' => $offices,
        'total' => count($offices)
    ]);
} catch (Exception $e) {
    respond_register(500, 'Failed to fetch offices');
}
?>