<?php
// api/designations/get_designations.php
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
    $countQuery = "SELECT COUNT(*) as total FROM designations";
    $countStmt = $pdo_system->prepare($countQuery);
    $countStmt->execute();
    $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $limit);
    
        if ($isLookupMode) {
                $query = "SELECT id, designation_name FROM designations WHERE COALESCE(designation_status, 'Active') = 'Active' ORDER BY designation_name ASC LIMIT :limit OFFSET :offset";
        } else {
                $query = "SELECT 
                                        id, 
                                        designation_name, 
                                        designation_description, 
                                        designation_status, 
                                        created_at, 
                                        updated_at 
                                    FROM designations 
                                    ORDER BY id DESC 
                                    LIMIT :limit OFFSET :offset";
        }
    
    $stmt = $pdo_system->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $designations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => 200,
        "message" => "Designations retrieved successfully",
        "data" => [
            "designations" => $designations,
            "total" => (int)$totalRecords,
            "currentPage" => $page,
            "totalPages" => $totalPages,
            "limit" => $limit
        ]
    ]);
    
} catch(PDOException $e) {
    error_log('get_designations failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Unable to fetch designations right now."
    ]);
}
?>