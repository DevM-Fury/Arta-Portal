<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 8;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    $offset = ($page - 1) * $limit;

    // Count with optional search
    $countSql = "SELECT COUNT(*) FROM designations WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $countSql .= " AND (designation_name ILIKE :q OR designation_description ILIKE :q)";
        $params[':q'] = "%{$search}%";
    }

    $countStmt = $pdo_system->prepare($countSql);
    $countStmt->execute($params);
    $totalRows = (int)$countStmt->fetchColumn();
    $totalPages = $limit > 0 ? (int)ceil($totalRows / $limit) : 1;

    // Fetch paginated results
    $sql = "SELECT id, designation_name, designation_description, designation_status, created_at, updated_at
            FROM designations
            WHERE 1=1";

    if ($search !== '') {
        $sql .= " AND (designation_name ILIKE :q OR designation_description ILIKE :q)";
    }

    $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo_system->prepare($sql);

    if ($search !== '') {
        $stmt->bindValue(':q', "%{$search}%", PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $designations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    respond_system(200, "Designations fetched successfully", [
        'designations' => $designations,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalRows' => $totalRows
    ]);

} catch (Exception $e) {
    respond_system(500, "Failed to fetch designations", ['error' => $e->getMessage()]);
}