<?php
// includes/config_system.php

// ✅ Load .env manually (optional kung may .env ka)
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

// ✅ Database credentials
$DB_HOST_SYS = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME_SYS = getenv('DB_NAME') ?: 'portal_db';
$DB_USER_SYS = getenv('DB_USER') ?: 'postgres';
$DB_PASS_SYS = getenv('DB_PASS') ?: 'strongpasswordsecret';

function system_apply_cors($methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']) {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Methods: ' . implode(', ', $methods));
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-Token');

    $origin = trim((string) ($_SERVER['HTTP_ORIGIN'] ?? ''));
    if ($origin !== '') {
        $originHost = strtolower((string) parse_url($origin, PHP_URL_HOST));
        $currentHost = strtolower((string) preg_replace('/:\d+$/', '', (string) ($_SERVER['HTTP_HOST'] ?? '')));
        $portalHost = strtolower((string) parse_url((string) getenv('PORTAL_BASE_URL'), PHP_URL_HOST));
        $allowedHosts = array_filter(array_unique([$currentHost, $portalHost, 'localhost', '127.0.0.1']));

        if ($originHost !== '' && in_array($originHost, $allowedHosts, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Vary: Origin');
        }
    }
}

function system_has_authenticated_session() {
    return !empty($_SESSION['user']['portal_id']) || !empty($_SESSION['portal_id']);
}

// ✅ CORS + JSON headers
system_apply_cors();

// ✅ Handle OPTIONS request (para hindi mag 403 preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ✅ Create PDO connection (PostgreSQL)
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
    error_log('config_system DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// ✅ Response helper
function respond_system($statusCode, $message, $data = null) {
    if ($statusCode >= 500 && $data !== null) {
        error_log('respond_system internal error payload: ' . json_encode($data));
        $data = null;
    }

    http_response_code($statusCode);
    echo json_encode([
        'status' => $statusCode,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}



// ✅ Upload directory
define('SYSTEM_UPLOAD_DIR', __DIR__ . '/../uploads/');
if (!file_exists(SYSTEM_UPLOAD_DIR)) {
    mkdir(SYSTEM_UPLOAD_DIR, 0777, true);
}



// ============================================
// ✅ PHPMailer / Email Configuration
// ============================================
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: 'abayanm1430@gmail.com');
define('SMTP_PASS', getenv('SMTP_PASS') ?: 'fiyejycuqkyjwtag');
define('SMTP_FROM', getenv('SMTP_FROM') ?: 'abayanm1430@gmail.com');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'ARTA Portal');
define('SMTP_ENCRYPTION', getenv('SMTP_ENCRYPTION') ?: 'tls');

// define('SMTP_HOST', 'smtp.office365.com');
// define('SMTP_PORT', 587);
// define('SMTP_USER', 'markabayan@arta.gov.ph');
// define('SMTP_PASS', 'YOUR_PASSWORD_HERE'); // from IT Admin
// define('SMTP_FROM', 'markabayan@arta.gov.ph');
// define('SMTP_FROM_NAME', 'ARTA Portal');
// define('SMTP_ENCRYPTION', 'tls');
