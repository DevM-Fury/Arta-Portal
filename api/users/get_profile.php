<?php
// ✅ Clean output & set JSON header early
ob_clean();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Prevent HTML warnings from leaking into JSON

include_once __DIR__ . '/../../includes/config_access.php';
session_start();

// ✅ Check session
if (empty($_SESSION['user']['portal_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in.']);
    exit;
}

$portal_id = $_SESSION['user']['portal_id'];

try {
    $sql = "
        SELECT 
            u.portal_id,
        u.national_id,

            u.firstname,
            u.lastname,
            u.email,
            u.role,
            COALESCE(o.office_name, 'N/A') AS office_name,
            COALESCE(d.division_name, 'N/A') AS division_name,
            COALESCE(un.unit_name, 'N/A') AS unit_name,
            COALESCE(desig.designation_name, 'N/A') AS designation_name,
            TO_CHAR(u.date_created, 'Month DD, YYYY') AS date_created
        FROM user_tbl u
        LEFT JOIN offices o ON u.office_id = o.id
        LEFT JOIN divisions d ON u.division_id = d.id
        LEFT JOIN units un ON u.unit_id = un.id
        LEFT JOIN designations desig ON u.designation_id = desig.id
        WHERE u.portal_id = :portal_id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':portal_id' => $portal_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

    // ✅ Return clean JSON
    echo json_encode(['status' => 'success', 'user' => $user]);

} catch (Throwable $e) {
    // ✅ Log error (optional: write to file)
    error_log("get_profile.php error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Internal server error.']);
}