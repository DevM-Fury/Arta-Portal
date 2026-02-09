<?php
// api/divisions/get_divisions.php
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
    $office_id = isset($_GET['office_id']) ? (int)$_GET['office_id'] : null;
    
    if ($office_id) {
        $query = "SELECT id, division_name, division_code, office_id FROM divisions WHERE status = 'active' AND office_id = :office_id ORDER BY division_name LIMIT :limit";
        $stmt = $pdo_system->prepare($query);
        $stmt->bindParam(':office_id', $office_id);
    } else {
        $query = "SELECT id, division_name, division_code, office_id FROM divisions WHERE status = 'active' ORDER BY division_name LIMIT :limit";
        $stmt = $pdo_system->prepare($query);
    }
    
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $divisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => 200,
        "message" => "Divisions retrieved successfully",
        "data" => [
            "divisions" => $divisions,
            "count" => count($divisions)
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