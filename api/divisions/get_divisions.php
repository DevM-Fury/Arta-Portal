<?php
// api/divisions/get_divisions.php
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
    
        if ($isLookupMode) {
                $query = "SELECT d.id, d.division_name, d.office_id FROM divisions d";
        } else {
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
        }
    
    $params = [];
    
    if ($office_id) {
        $query .= " WHERE d.office_id = :office_id";
        $params[':office_id'] = $office_id;
    } elseif ($isLookupMode) {
        $query .= " WHERE COALESCE(d.division_status, 'Active') = 'Active'";
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
    error_log('get_divisions failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Unable to fetch divisions right now."
    ]);
}
?>