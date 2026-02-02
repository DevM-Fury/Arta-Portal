<?php
// api/users/get_users.php
require_once __DIR__ . '/../../includes/config_system.php';
include_once __DIR__ . '/../../includes/log_helper.php';

try {
    // single user by id
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        $id = $_GET['id'];
        $stmt = $pdo_system->prepare("SELECT portal_id, national_id, firstname, lastname, contact, email, role, date_created, modified_date FROM user_tbl WHERE portal_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        if (!$user) {
            respond_system(404, 'User not found');
        }
        respond_system(200, 'OK', $user);
    }

    // list with pagination + search
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, intval($_GET['limit'] ?? 5));
    $offset = ($page - 1) * $limit;
    $q = trim($_GET['q'] ?? '');

    if ($q !== '') {
        // simple search across firstname, lastname, email, national_id
        $like = "%$q%";
        $countStmt = $pdo_system->prepare("SELECT COUNT(*) FROM user_tbl WHERE firstname ILIKE :q OR lastname ILIKE :q OR email ILIKE :q OR CAST(national_id AS TEXT) ILIKE :q");
        $countStmt->execute([':q' => $like]);
        $totalRecords = (int)$countStmt->fetchColumn();

        $stmt = $pdo_system->prepare("SELECT portal_id, national_id, firstname, lastname, contact, email, role, date_created FROM user_tbl WHERE firstname ILIKE :q OR lastname ILIKE :q OR email ILIKE :q OR CAST(national_id AS TEXT) ILIKE :q ORDER BY date_created DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':q', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $countStmt = $pdo_system->query("SELECT COUNT(*) FROM user_tbl");
        $totalRecords = (int)$countStmt->fetchColumn();

        $stmt = $pdo_system->prepare("SELECT portal_id, national_id, firstname, lastname, contact, email, role, date_created FROM user_tbl ORDER BY date_created DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    }

    $users = $stmt->fetchAll();
    $totalPages = $totalRecords === 0 ? 1 : (int)ceil($totalRecords / $limit);

    respond_system(200, 'OK', [
        'users' => $users,
        'totalRecords' => $totalRecords,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);

} catch (Exception $e) {
    error_log("get_users error: " . $e->getMessage());
    respond_system(500, 'Server error');
}