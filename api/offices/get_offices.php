<?php
// api/offices/get_offices.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../includes/config_system.php';

try {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 1000;
    
    // ✅ FIXED: inalis ang 'office_code' – wala sa table
    $query = "SELECT id, office_name FROM offices WHERE status = 'Active' ORDER BY office_name LIMIT :limit";
    $stmt = $pdo_system->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $offices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => 200,
        "message" => "Offices retrieved successfully",
        "data" => [
            "offices" => $offices,
            "count" => count($offices)
        ]
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>