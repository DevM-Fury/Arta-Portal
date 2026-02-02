<?php
// get_systems_subadmin.php
error_reporting(0);
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../includes/config_access.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['user']['portal_id'])) {
    echo json_encode([
        'status' => 401,
        'message' => 'Not logged in.'
    ]);
    exit;
}

$portal_id = $_SESSION['user']['portal_id'];
$user_role = $_SESSION['user']['role'] ?? 'user';

try {
    if ($user_role === 'admin') {
        // ✅ Admin can view ALL systems
        $stmt = $pdo->query("
            SELECT id AS system_id, system_name
            FROM systems
            ORDER BY system_name ASC
        ");
    } else {
        // ✅ Subadmin sees only systems assigned to them
        $stmt = $pdo->prepare("
            SELECT s.id AS system_id, s.system_name
            FROM user_system_access usa
            INNER JOIN systems s ON s.id = usa.system_id
            WHERE usa.portal_id = :portal_id
            ORDER BY s.system_name ASC
        ");
        $stmt->execute([':portal_id' => $portal_id]);
    }

    $systems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($systems ?: []);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 500,
        'message' => 'Database error.',
        'error' => $e->getMessage()
    ]);
}