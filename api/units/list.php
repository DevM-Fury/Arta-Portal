<?php
require_once __DIR__ . '/../../includes/config_register.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

try {
    $totalQuery = $pdo_system->query("SELECT COUNT(*) FROM units");
    $totalRows = (int)$totalQuery->fetchColumn();
    $totalPages = $limit > 0 ? (int)ceil($totalRows / $limit) : 1;

    $sql = "
        SELECT u.id, u.unit_name, u.unit_description, u.unit_status, u.division_id,
               d.division_name, u.created_at, u.updated_at
        FROM units u
        LEFT JOIN divisions d ON u.division_id = d.id
        ORDER BY u.id ASC
        LIMIT :limit OFFSET :offset
    ";
    $stmt = $pdo_system->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

    respond_system(200, "Units fetched successfully", [
        'units' => $units,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);
} catch (Exception $e) {
    respond_system(500, "Failed to fetch units", ['error' => $e->getMessage()]);
}