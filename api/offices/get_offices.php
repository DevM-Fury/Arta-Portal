<?php
require_once __DIR__ . '/../../includes/config_system.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

try {
    // Count total rows
    $totalQuery = $pdo_system->query("SELECT COUNT(*) FROM offices");
    $totalRows = (int)$totalQuery->fetchColumn();
    $totalPages = $limit > 0 ? ceil($totalRows / $limit) : 1;

    // Fetch paginated rows
    $stmt = $pdo_system->prepare("
        SELECT id, office_name, description, status, created_at, updated_at
        FROM offices
        ORDER BY id ASC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $offices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    respond_system(200, 'Offices fetched successfully', [
        'offices' => $offices,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);

} catch (Exception $e) {
    respond_system(500, 'Failed to fetch offices', ['error' => $e->getMessage()]);
}