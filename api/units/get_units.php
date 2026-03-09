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
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    $offset = ($page - 1) * $limit;
    $division_id = isset($_GET['division_id']) ? (int)$_GET['division_id'] : null;
    
    // Base query for counting
    $countQuery = "SELECT COUNT(*) as total FROM units";
    $countParams = [];
    
    if ($division_id) {
        $countQuery .= " WHERE division_id = :division_id";
        $countParams[':division_id'] = $division_id;
    }
    
    $countStmt = $pdo_system->prepare($countQuery);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $limit);
    
    // ✅ FIXED: Select ALL fields needed by the frontend and JOIN with divisions table
    $query = "SELECT 
                u.id, 
                u.unit_name, 
                u.unit_description, 
                u.unit_status, 
                u.division_id,
                u.created_at, 
                u.updated_at,
                d.division_name
              FROM units u
              LEFT JOIN divisions d ON u.division_id = d.id";
    
    $params = [];
    
    if ($division_id) {
        $query .= " WHERE u.division_id = :division_id";
        $params[':division_id'] = $division_id;
    }
    
    $query .= " ORDER BY u.id DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo_system->prepare($query);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => 200,
        "message" => "Units retrieved successfully",
        "data" => [
            "units" => $units,
            "total" => (int)$totalRecords,
            "currentPage" => $page,
            "totalPages" => $totalPages,
            "limit" => $limit
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



