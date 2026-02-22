<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start(); // siguraduhing may session

try {
    require_once __DIR__ . '/../../includes/config_access.php'; // may $pod

    // Kunin ang portal_id ng kasalukuyang user
    if (!isset($_SESSION['user']['portal_id'])) {
        // Kung walang session, return empty array o error
        echo json_encode([]);
        exit;
    }
    $currentUserId = $_SESSION['user']['portal_id'];

    // Query: assignments na ang assigned_by ay ang current user
    $query = "
        SELECT 
            ua.access_id,
            ua.portal_id,
            ua.system_id,
            ua.role,
            ua.date_assigned,
            u.firstname,
            u.lastname,
            s.system_name,
            CONCAT(au.firstname, ' ', au.lastname) AS assigned_by_name
        FROM user_system_access ua
        JOIN user_tbl u ON ua.portal_id = u.portal_id
        JOIN systems s ON ua.system_id = s.id
        LEFT JOIN user_tbl au ON ua.assigned_by = au.portal_id
        WHERE ua.assigned_by = :current_user
        ORDER BY ua.date_assigned DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':current_user' => $currentUserId]);
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($assignments);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit;
}