<?php
// api/designations/get_designations.php
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
    
    $query = "SELECT id, designation_name, designation_code, level FROM designations WHERE status = 'active' ORDER BY level, designation_name LIMIT :limit";
    $stmt = $pdo_system->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $designations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => 200,
        "message" => "Designations retrieved successfully",
        "data" => [
            "designations" => $designations,
            "count" => count($designations)
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