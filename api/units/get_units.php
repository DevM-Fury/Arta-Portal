<?php
// api/units/get_units.php
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
    $division_id = isset($_GET['division_id']) ? (int)$_GET['division_id'] : null;
    
    if ($division_id) {
        $query = "SELECT id, unit_name, unit_code, division_id FROM units WHERE status = 'active' AND division_id = :division_id ORDER BY unit_name LIMIT :limit";
        $stmt = $pdo_system->prepare($query);
        $stmt->bindParam(':division_id', $division_id);
    } else {
        $query = "SELECT id, unit_name, unit_code, division_id FROM units WHERE status = 'active' ORDER BY unit_name LIMIT :limit";
        $stmt = $pdo_system->prepare($query);
    }
    
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => 200,
        "message" => "Units retrieved successfully",
        "data" => [
            "units" => $units,
            "count" => count($units)
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