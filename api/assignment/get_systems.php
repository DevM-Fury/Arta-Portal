<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config_access.php';

$stmt = $pdo->query("SELECT id, system_name FROM systems ORDER BY system_name ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);