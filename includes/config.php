<?php
function load_dotenv($path) {
    if (!file_exists($path)) return;
    foreach(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        [$name, $val] = explode('=', $line, 2);
        putenv("$name=$val");
    }
}
load_dotenv(__DIR__ . '/../.env');

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'portal_db';
$DB_USER = getenv('DB_USER') ?: 'postgres';
$DB_PASS = getenv('DB_PASS') ?: 'strongpasswordsecret';
$ALLOWED_ORIGIN = getenv('ALLOWED_ORIGIN') ?: 'http://127.0.0.1';

function respond($statusCode, $data) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}
