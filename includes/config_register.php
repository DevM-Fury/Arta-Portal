<?php
// config_register.php
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

// ✅ Database credentials (same as your existing system)
$DB_HOST_REG = getenv('DB_HOST') ?: 'localhost';
$DB_NAME_REG = getenv('DB_NAME') ?: 'portal_db';
$DB_USER_REG = getenv('DB_USER') ?: 'postgres';
$DB_PASS_REG = getenv('DB_PASS') ?: 'secret';

// ✅ CORS + JSON headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// ✅ Handle OPTIONS request (para hindi mag 403 preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ✅ Create PDO connection (PostgreSQL)
try {
    $pdo_register = new PDO(
        "pgsql:host=$DB_HOST_REG;dbname=$DB_NAME_REG",
        $DB_USER_REG,
        $DB_PASS_REG,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Database connection failed', 'error' => $e->getMessage()]);
    exit;
}

// ✅ Response helper
function respond_register($statusCode, $message, $data = null) {
    http_response_code($statusCode);
    $response = [
        'status' => $statusCode,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

// ✅ Validation functions
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && strpos($email, '@arta.gov.ph') !== false;
}

function validate_password($password) {
    return strlen($password) >= 6 && preg_match('/^[a-zA-Z0-9]+$/', $password);
}

function validate_contact($contact) {
    return preg_match('/^9\d{10}$/', $contact);
}

function validate_national_id($national_id) {
    return preg_match('/^\d{12}$/', $national_id);
}

// ✅ Check if required fields are present
function check_required_fields($data, $required_fields) {
    $missing = [];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $missing[] = $field;
        }
    }
    
    if (!empty($missing)) {
        respond_register(400, "Missing required fields: " . implode(', ', $missing));
    }
}

// ✅ Check if email exists
function check_existing_email($email, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT portal_id FROM user_tbl WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        respond_register(500, "Database error checking email");
    }
}

// ✅ Check if national ID exists
function check_existing_national_id($national_id, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT portal_id FROM user_tbl WHERE national_id = :national_id");
        $stmt->execute([':national_id' => $national_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        respond_register(500, "Database error checking national ID");
    }
}

// ✅ Hash password
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// ✅ Generate verification token
function generate_verification_token() {
    return bin2hex(random_bytes(32));
}

// ✅ Log registration activity
function log_registration_activity($user_id, $action, $pdo) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO registration_logs (user_id, action, ip_address, user_agent, created_at)
            VALUES (:user_id, :action, :ip_address, :user_agent, NOW())
        ");
        
        $stmt->execute([
            ':user_id' => $user_id,
            ':action' => $action,
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ]);
    } catch (Exception $e) {
        // Silent fail for logging
    }
}

// ✅ Send email notification (placeholder function)
function send_registration_email($email, $firstname, $action = 'registration') {
    // This is a placeholder. Implement actual email sending here.
    // You can use PHPMailer, SendGrid, or any other email service.
    return true;
}

// ✅ Required fields for registration
$REG_REQUIRED_FIELDS = [
    'firstname',
    'lastname', 
    'national_id',
    'contact',
    'email',
    'password',
    'office_id',
    'division_id',
    'unit_id',
    'designation_id'
];

// ✅ Sanitize input data
function sanitize_input($data) {
    $sanitized = [];
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        } else {
            $sanitized[$key] = $value;
        }
    }
    return $sanitized;
}

// ✅ Check if table exists
function check_table_exists($table_name, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = :table_name)");
        $stmt->execute([':table_name' => $table_name]);
        return $stmt->fetchColumn();
    } catch (Exception $e) {
        return false;
    }
}

// ✅ Ensure required tables exist
function ensure_tables_exist($pdo) {
    $required_tables = ['user_tbl', 'offices', 'divisions', 'units', 'designations'];
    
    foreach ($required_tables as $table) {
        if (!check_table_exists($table, $pdo)) {
            respond_register(500, "Required table '$table' does not exist in database");
        }
    }
}

// ✅ Get all active offices
function get_active_offices($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT id, office_name 
            FROM offices 
            WHERE status = 'active'
            ORDER BY office_name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        respond_register(500, "Failed to fetch offices");
    }
}

// ✅ Get all active divisions
function get_active_divisions($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT id, division_name, office_id 
            FROM divisions 
            WHERE division_status = 'active'
            ORDER BY division_name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        respond_register(500, "Failed to fetch divisions");
    }
}

// ✅ Get all active units
function get_active_units($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT id, unit_name, division_id 
            FROM units 
            WHERE unit_status = 'active'
            ORDER BY unit_name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        respond_register(500, "Failed to fetch units");
    }
}

// ✅ Get all active designations
function get_active_designations($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT id, designation_name 
            FROM designations 
            WHERE designation_status = 'active'
            ORDER BY designation_name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        respond_register(500, "Failed to fetch designations");
    }
}

// ✅ Validate all dropdown IDs exist
function validate_dropdown_ids($office_id, $division_id, $unit_id, $designation_id, $pdo) {
    try {
        // Check office exists
        $stmt = $pdo->prepare("SELECT id FROM offices WHERE id = :id AND status = 'active'");
        $stmt->execute([':id' => $office_id]);
        if (!$stmt->fetch()) {
            return "Invalid office selected";
        }
        
        // Check division exists and belongs to office
        $stmt = $pdo->prepare("SELECT id FROM divisions WHERE id = :id AND office_id = :office_id AND division_status = 'active'");
        $stmt->execute([':id' => $division_id, ':office_id' => $office_id]);
        if (!$stmt->fetch()) {
            return "Invalid division or division does not belong to selected office";
        }
        
        // Check unit exists and belongs to division
        $stmt = $pdo->prepare("SELECT id FROM units WHERE id = :id AND division_id = :division_id AND unit_status = 'active'");
        $stmt->execute([':id' => $unit_id, ':division_id' => $division_id]);
        if (!$stmt->fetch()) {
            return "Invalid unit or unit does not belong to selected division";
        }
        
        // Check designation exists
        $stmt = $pdo->prepare("SELECT id FROM designations WHERE id = :id AND designation_status = 'active'");
        $stmt->execute([':id' => $designation_id]);
        if (!$stmt->fetch()) {
            return "Invalid designation selected";
        }
        
        return true;
    } catch (Exception $e) {
        return "Error validating dropdown selections";
    }
}

// ✅ Upload directory
define('REG_UPLOAD_DIR', __DIR__ . '/../uploads/registrations/');
if (!file_exists(REG_UPLOAD_DIR)) {
    mkdir(REG_UPLOAD_DIR, 0777, true);
}

// ✅ Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// ✅ Rate limiting (simple implementation)
session_start();
function check_rate_limit($limit = 10, $time_window = 3600) {
    if (!isset($_SESSION['api_requests'])) {
        $_SESSION['api_requests'] = [];
    }
    
    $now = time();
    $_SESSION['api_requests'] = array_filter($_SESSION['api_requests'], function($timestamp) use ($now, $time_window) {
        return ($now - $timestamp) < $time_window;
    });
    
    if (count($_SESSION['api_requests']) >= $limit) {
        respond_register(429, "Too many requests. Please try again later.");
    }
    
    $_SESSION['api_requests'][] = $now;
}

// ✅ JSON input helper
function get_json_input() {
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        respond_register(400, "Invalid JSON input");
    }
    return $input;
}

// ✅ Default timezone
date_default_timezone_set('Asia/Manila');

// ✅ Ensure tables exist on first run
if (!defined('TABLES_CHECKED')) {
    ensure_tables_exist($pdo_register);
    define('TABLES_CHECKED', true);
}
?>