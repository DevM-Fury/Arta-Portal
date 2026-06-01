<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/api_guard.php';

api_require_method('GET');
api_require_user(['admin', 'subadmin']);

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';

$allowed = [
    'office' => 'office_id',
    'division' => 'division_id',
    'unit' => 'unit_id',
    'designation' => 'designation_id'
];

$where = '';
$params = [];

if ($type && isset($allowed[$type]) && $id !== '') {
    $col = $allowed[$type];
    $where = "WHERE u.$col = :val";
    $params[':val'] = $id;
}

$sql = "
SELECT u.portal_id, u.firstname, u.lastname,
       o.office_name, d.division_name, un.unit_name, des.designation_name
FROM user_tbl u
LEFT JOIN offices o ON u.office_id = o.id
LEFT JOIN divisions d ON u.division_id = d.id
LEFT JOIN units un ON u.unit_id = un.id
LEFT JOIN designations des ON u.designation_id = des.id
$where
ORDER BY u.lastname, u.firstname
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Throwable $e) {
    api_json_response(500, ['status' => 500, 'message' => 'Unable to fetch users right now']);
}