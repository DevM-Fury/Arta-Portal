<?php
require_once __DIR__ . '/../../includes/config_system.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

try {
    $totalQuery = $pdo_system->query("SELECT COUNT(*) FROM divisions");
    $totalRows = (int)$totalQuery->fetchColumn();
    $totalPages = $limit > 0 ? (int)ceil($totalRows / $limit) : 1;

    $sql = "
        SELECT d.id, d.division_name, d.division_description, d.division_status, d.office_id,
               o.office_name, d.created_at, d.updated_at
        FROM divisions d
        LEFT JOIN offices o ON d.office_id = o.id
        ORDER BY d.id ASC
        LIMIT :limit OFFSET :offset
    ";
    $stmt = $pdo_system->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $divisions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    respond_system(200, "Divisions fetched successfully", [
        'divisions' => $divisions,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);
} catch (Exception $e) {
    respond_system(500, "Failed to fetch divisions", ['error' => $e->getMessage()]);
}