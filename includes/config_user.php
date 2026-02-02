<?php
// ✅ Load .env manually (optional, kung may .env ka)
function load_dotenv_system($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        [$name, $val] = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($val));
    }
}
load_dotenv_system(__DIR__ . '/../.env');

// ✅ PostgreSQL Database Credentials
$DB_HOST_SYS = getenv('DB_HOST') ?: 'localhost';
$DB_NAME_SYS = getenv('DB_NAME') ?: 'portal_db';
$DB_USER_SYS = getenv('DB_USER') ?: 'postgres';
$DB_PASS_SYS = getenv('DB_PASS') ?: 'secret'; // lagyan mo kung may password ka

// ✅ Set JSON + CORS headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// ✅ Handle OPTIONS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ✅ Create PDO connection for PostgreSQL
try {
    $pdo_system = new PDO(
        "pgsql:host=$DB_HOST_SYS;dbname=$DB_NAME_SYS",
        $DB_USER_SYS,
        $DB_PASS_SYS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Database connection failed',
        'error' => $e->getMessage()
    ]);
    exit;
}

// ✅ Response helper function
function respond_system($statusCode, $message, $data = null) {
    http_response_code($statusCode);
    echo json_encode([
        'status' => $statusCode,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>