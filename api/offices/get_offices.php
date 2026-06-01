<?php
// api/offices/get_offices.php
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
    
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM offices";
    $countStmt = $pdo_system->prepare($countQuery);
    $countStmt->execute();
    $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $limit);
    
        if ($isLookupMode) {
                $query = "SELECT id, office_name FROM offices WHERE COALESCE(status, 'Active') = 'Active' ORDER BY office_name ASC LIMIT :limit OFFSET :offset";
        } else {
                $query = "SELECT 
                                        id, 
                                        office_name, 
                                        description, 
                                        status, 
                                        created_at, 
                                        updated_at 
                                    FROM offices 
                                    ORDER BY id DESC 
                                    LIMIT :limit OFFSET :offset";
        }
    
    $stmt = $pdo_system->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $offices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => 200,
        "message" => "Offices retrieved successfully",
        "data" => [
            "offices" => $offices,
            "total" => (int)$totalRecords,
            "currentPage" => $page,
            "totalPages" => $totalPages,
            "limit" => $limit
        ]
    ]);
    
} catch(PDOException $e) {
    error_log('get_offices failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Unable to fetch offices right now."
    ]);
}
?>