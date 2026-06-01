<?php
// api/units/get_units.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../includes/config_system.php';

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD'], true)) {
    respond_system(405, 'Method not allowed');
}

$isAuthenticated = system_has_authenticated_session();
$isLookupMode = !$isAuthenticated && (string) ($_GET['lookup'] ?? '') === '1';

if (!$isAuthenticated && !$isLookupMode) {
    respond_system(401, 'Authentication required');
}

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
    
        if ($isLookupMode) {
                $query = "SELECT u.id, u.unit_name, u.division_id FROM units u";
        } else {
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
        }
    
    $params = [];
    
    if ($division_id) {
        $query .= " WHERE u.division_id = :division_id";
        $params[':division_id'] = $division_id;
    } elseif ($isLookupMode) {
        $query .= " WHERE COALESCE(u.unit_status, 'Active') = 'Active'";
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
    error_log('get_units failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Unable to fetch units right now."
    ]);
}
?>



