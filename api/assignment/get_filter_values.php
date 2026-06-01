<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/api_guard.php';

api_require_method('GET');
api_require_user(['admin', 'subadmin']);

$type = $_GET['type'] ?? '';

$tables = [
    'office'      => ['table'=>'offices',      'id'=>'id', 'name'=>'office_name'],
    'division'    => ['table'=>'divisions',    'id'=>'id', 'name'=>'division_name'],
    'unit'        => ['table'=>'units',        'id'=>'id', 'name'=>'unit_name'],
    'designation' => ['table'=>'designations', 'id'=>'id', 'name'=>'designation_name']
];

if (!$type || !isset($tables[$type])) {
    echo json_encode([]);
    exit;
}

$table = $tables[$type]['table'];
$idCol = $tables[$type]['id'];
$nameCol = $tables[$type]['name'];

try {
    $stmt = $pdo->query("SELECT $idCol AS id, $nameCol AS name FROM $table ORDER BY $nameCol ASC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
} catch (Exception $e) {
    api_json_response(500, ['status' => 500, 'message' => 'Unable to load filter values']);
}