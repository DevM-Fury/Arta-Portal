<?php
// includes/config.php

// ✅ Load environment variables
function load_dotenv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        [$name, $val] = explode('=', $line, 2);
        putenv("$name=$val");
    }
}
load_dotenv(__DIR__ . '/../.env');

// ✅ Database credentials
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'portal_db';
$DB_USER = getenv('DB_USER') ?: 'postgres';
$DB_PASS = getenv('DB_PASS') ?: 'strongpasswordsecret';

// ✅ PostgreSQL Connection using PDO
try {
    $conn = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// ✅ Allow access globally
global $conn;

// // ✅ Session & security configs (but don't start session here!)
// ini_set('session.cookie_httponly', 1);
// ini_set('session.use_strict_mode', 1);
// ini_set('session.cookie_samesite', 'Lax'); // or 'None' if using HTTPS

// ✅ Helper function for API-style responses
function respond($statusCode, $data) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}
