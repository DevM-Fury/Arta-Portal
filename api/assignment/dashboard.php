<?php
session_start();
include_once __DIR__ . '/../includes/config_access.php'; // PDO connection

// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

try {
    // Get all systems
    $stmt = $pdo->query("SELECT * FROM systems ORDER BY system_name ASC");
    $systems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count assigned users for each system
    $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM user_system_access WHERE system_id = ?");
} catch (PDOException $e) {
    error_log('assignment dashboard failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Unable to load dashboard data.');
}
?>