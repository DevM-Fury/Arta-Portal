<?php
header('Content-Type: application/json');
session_start();
include_once __DIR__ . '/../../includes/config_access.php'; // PDO connection

try {
    if (!isset($_SESSION['user'])) {
        echo json_encode([]);
        exit;
    }

    $currentUserId = $_SESSION['user']['portal_id'];
    $currentUserRole = $_SESSION['user']['role'] ?? 'subadmin'; // default role

    if ($currentUserRole === 'admin') {
        // Admin sees all systems
        $stmt = $pdo->query("
            SELECT 
                s.id AS system_id,
                s.system_name,
                s.system_url,
                u.firstname AS user_firstname,
                u.lastname AS user_lastname,
                'admin' AS role
            FROM systems s
            LEFT JOIN user_tbl u ON u.portal_id = :currentUser
        ");
        $stmt->bindValue(':currentUser', $currentUserId);
        $stmt->execute();
        $systems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Non-admin: only assigned systems
        $stmt = $pdo->prepare("
            SELECT 
                ua.portal_id,
                ua.system_id,
                ua.role,
                ua.date_assigned,
                u.firstname AS user_firstname,
                u.lastname AS user_lastname,
                s.system_name,
                s.system_url
            FROM user_system_access ua
            LEFT JOIN user_tbl u ON u.portal_id = ua.portal_id
            LEFT JOIN systems s ON s.id = ua.system_id
            WHERE ua.portal_id = :portal
            ORDER BY ua.date_assigned DESC
        ");
        $stmt->execute([':portal' => $currentUserId]);
        $systems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($systems ?: []);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 500,
        'message' => 'Failed to fetch assigned systems.',
        'error' => $e->getMessage()
    ]);
}