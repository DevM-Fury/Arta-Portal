<?php
// api/users/create_user.php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) respond_system(400, 'Invalid JSON');

    // required fields
    $required = ['national_id','firstname','lastname','contact','email','password'];
    foreach ($required as $r) {
        if (empty($payload[$r])) respond_system(400, "Missing field: $r");
    }

    // basic email validation
    if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) respond_system(400, 'Invalid email');

    // national_id numeric check
    if (!is_numeric($payload['national_id'])) respond_system(400, 'National ID must be numeric');

    // check unique email or national_id
    $stmt = $pdo_system->prepare("SELECT portal_id FROM user_tbl WHERE email = :email OR national_id = :nid LIMIT 1");
    $stmt->execute([':email' => $payload['email'], ':nid' => $payload['national_id']]);
    if ($stmt->fetch()) respond_system(409, 'Email or National ID already exists');

    $hashed = password_hash($payload['password'], PASSWORD_DEFAULT);

    $insert = $pdo_system->prepare("INSERT INTO user_tbl (national_id, firstname, lastname, contact, email, password, role) VALUES (:nid, :fn, :ln, :ct, :em, :pw, :rl) RETURNING portal_id, date_created");
    $insert->execute([
        ':nid' => $payload['national_id'],
        ':fn' => $payload['firstname'],
        ':ln' => $payload['lastname'],
        ':ct' => $payload['contact'],
        ':em' => $payload['email'],
        ':pw' => $hashed,
        ':rl' => $payload['role'] ?? 'user'
    ]);

    $row = $insert->fetch();
    respond_system(200, 'User created', ['portal_id' => $row['portal_id'], 'date_created' => $row['date_created']]);

} catch (PDOException $e) {
    error_log("create_user error: " . $e->getMessage());
    respond_system(500, 'Server error');
}