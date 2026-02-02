<?php
require_once __DIR__ . '/../../includes/config_system.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    // Count total rows
    $totalQuery = $pdo_system->query("SELECT COUNT(*) FROM systems");
    $totalRows = $totalQuery->fetchColumn();
    $totalPages = ceil($totalRows / $limit);

    // Fetch paginated rows
    $stmt = $pdo_system->prepare("SELECT * FROM systems ORDER BY id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $systems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    respond_system(200, 'Systems fetched successfully', [
        'systems' => $systems,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);

} catch (Exception $e) {
    respond_system(500, 'Failed to fetch systems', ['error' => $e->getMessage()]);
}