<?php
// api/users/delete_user.php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) respond_system(400, 'Invalid JSON');

    $portal_id = $payload['portal_id'] ?? '';
    if (!$portal_id) respond_system(400, 'Missing portal_id');

    $stmt = $pdo_system->prepare("DELETE FROM user_tbl WHERE portal_id = :id");
    $stmt->execute([':id' => $portal_id]);

    if ($stmt->rowCount() === 0) {
        respond_system(404, 'User not found or already deleted');
    }

    respond_system(200, 'User deleted');

} catch (PDOException $e) {
    error_log("delete_user error: " . $e->getMessage());
    respond_system(500, 'Server error');
}