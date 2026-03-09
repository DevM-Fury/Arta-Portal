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
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    $offset = ($page - 1) * $limit;
    $office_id = isset($_GET['office_id']) ? (int)$_GET['office_id'] : null;
    
    // Base query for counting
    $countQuery = "SELECT COUNT(*) as total FROM divisions";
    $countParams = [];
    
    if ($office_id) {
        $countQuery .= " WHERE office_id = :office_id";
        $countParams[':office_id'] = $office_id;
    }
    
    $countStmt = $pdo_system->prepare($countQuery);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $limit);
    
    // ✅ FIXED: Select ALL fields needed by the frontend and JOIN with offices table
    $query = "SELECT 
                d.id, 
                d.division_name, 
                d.division_description, 
                d.division_status, 
                d.office_id,
                d.created_at, 
                d.updated_at,
                o.office_name
              FROM divisions d
              LEFT JOIN offices o ON d.office_id = o.id";
    
    $params = [];
    
    if ($office_id) {
        $query .= " WHERE d.office_id = :office_id";
        $params[':office_id'] = $office_id;
    }
    
    $query .= " ORDER BY d.id DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo_system->prepare($query);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $divisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => 200,
        "message" => "Divisions retrieved successfully",
        "data" => [
            "divisions" => $divisions,
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