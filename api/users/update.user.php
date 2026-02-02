<?php
// api/users/update_user.php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) respond_system(400, 'Invalid JSON');

    $portal_id = $payload['portal_id'] ?? '';
    if (!$portal_id) respond_system(400, 'Missing portal_id');

    // check exists
    $stmt = $pdo_system->prepare("SELECT portal_id, email FROM user_tbl WHERE portal_id = :id LIMIT 1");
    $stmt->execute([':id' => $portal_id]);
    $user = $stmt->fetch();
    if (!$user) respond_system(404, 'User not found');

    $fields = [];
    $params = [':id' => $portal_id];

    if (!empty($payload['firstname'])) {
        $fields[] = "firstname = :firstname";
        $params[':firstname'] = $payload['firstname'];
    }
    if (!empty($payload['lastname'])) {
        $fields[] = "lastname = :lastname";
        $params[':lastname'] = $payload['lastname'];
    }
    if (!empty($payload['contact'])) {
        $fields[] = "contact = :contact";
        $params[':contact'] = $payload['contact'];
    }
    if (!empty($payload['role'])) {
        $fields[] = "role = :role";
        $params[':role'] = $payload['role'];
    }
    if (!empty($payload['email']) && $payload['email'] !== $user['email']) {
        if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) respond_system(400, 'Invalid email');
        // check unique
        $chk = $pdo_system->prepare("SELECT portal_id FROM user_tbl WHERE email = :email AND portal_id != :id LIMIT 1");
        $chk->execute([':email' => $payload['email'], ':id' => $portal_id]);
        if ($chk->fetch()) respond_system(409, 'Email already in use');
        $fields[] = "email = :email";
        $params[':email'] = $payload['email'];
    }
    if (!empty($payload['password'])) {
        $fields[] = "password = :password";
        $params[':password'] = password_hash($payload['password'], PASSWORD_DEFAULT);
    }

    if (empty($fields)) respond_system(400, 'No fields to update');

    // always update modified_date
    $fields[] = "modified_date = NOW()";

    $sql = "UPDATE user_tbl SET " . implode(', ', $fields) . " WHERE portal_id = :id";
    $upd = $pdo_system->prepare($sql);
    $upd->execute($params);

    respond_system(200, 'User updated');

} catch (PDOException $e) {
    error_log("update_user error: " . $e->getMessage());
    respond_system(500, 'Server error');
}