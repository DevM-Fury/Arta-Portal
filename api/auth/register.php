<?php
// api/auth/register.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include the existing config_system.php instead of Database class
require_once __DIR__ . '/../../includes/config_system.php';

// Get posted data
$raw_data = file_get_contents("php://input");
error_log("Received data: " . $raw_data);

$data = json_decode($raw_data);

if(empty($data)) {
    http_response_code(400);
    echo json_encode([
        "status" => 400,
        "message" => "No data received.",
        "raw_data" => $raw_data
    ]);
    exit();
}

// Check required fields
$required_fields = ['national_id', 'firstname', 'lastname', 'email', 'password', 'contact'];
$missing_fields = [];

foreach($required_fields as $field) {
    if(empty($data->$field)) {
        $missing_fields[] = $field;
    }
}

if(!empty($missing_fields)) {
    http_response_code(400);
    echo json_encode([
        "status" => 400,
        "message" => "Missing required fields: " . implode(', ', $missing_fields),
        "received_data" => $data
    ]);
    exit();
}

try {
    // Prepare data
    $national_id = $data->national_id;
    $firstname = htmlspecialchars(strip_tags($data->firstname));
    $lastname = htmlspecialchars(strip_tags($data->lastname));
    $email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    $password = $data->password;
    $contact = $data->contact;
    $office_id = isset($data->office_id) ? $data->office_id : null;
    $division_id = isset($data->division_id) ? $data->division_id : null;
    $unit_id = isset($data->unit_id) ? $data->unit_id : null;
    $designation_id = isset($data->designation_id) ? $data->designation_id : null;
    $role = 'user';
    
    error_log("Processing registration for: " . $email);
    
    // Check if email exists
    $check_email_query = "SELECT COUNT(*) FROM user_tbl WHERE email = :email";
    $check_email_stmt = $pdo_system->prepare($check_email_query);
    $check_email_stmt->bindParam(':email', $email);
    
    if(!$check_email_stmt->execute()) {
        error_log("Email check failed");
        $error_info = $check_email_stmt->errorInfo();
        error_log("Error info: " . print_r($error_info, true));
    }
    
    $email_exists = $check_email_stmt->fetchColumn();
    
    if($email_exists > 0) {
        error_log("Email already exists: " . $email);
        http_response_code(400);
        echo json_encode([
            "status" => 400,
            "message" => "Email already registered."
        ]);
        exit();
    }
    
    // Generate UUID for portal_id
    $portal_id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert query - POSTGRESQL VERSION
    $query = "INSERT INTO user_tbl 
              (portal_id, national_id, firstname, lastname, email, password, contact, 
               office_id, division_id, unit_id, designation_id, role, date_created) 
              VALUES 
              (:portal_id, :national_id, :firstname, :lastname, :email, :password, :contact,
               :office_id, :division_id, :unit_id, :designation_id, :role, NOW())";
    
    error_log("Query: " . $query);
    
    $stmt = $pdo_system->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':portal_id', $portal_id);
    $stmt->bindParam(':national_id', $national_id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':office_id', $office_id);
    $stmt->bindParam(':division_id', $division_id);
    $stmt->bindParam(':unit_id', $unit_id);
    $stmt->bindParam(':designation_id', $designation_id);
    
    // Execute
    if($stmt->execute()){
        $last_id = $pdo_system->lastInsertId();
        error_log("Registration successful. ID: " . $last_id);
        
        http_response_code(201);
        echo json_encode([
            "status" => 201,
            "message" => "Registration successful!",
            "data" => [
                "portal_id" => $portal_id,
                "email" => $email,
                "name" => $firstname . " " . $lastname
            ]
        ]);
    } else {
        $error_info = $stmt->errorInfo();
        error_log("Insert failed: " . print_r($error_info, true));
        
        http_response_code(500);
        echo json_encode([
            "status" => 500,
            "message" => "Insert failed.",
            "error_info" => $error_info
        ]);
    }
    
} catch(PDOException $exception) {
    error_log("PDO Exception: " . $exception->getMessage());
    error_log("Trace: " . $exception->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Database exception: " . $exception->getMessage()
    ]);
} catch(Exception $exception) {
    error_log("General Exception: " . $exception->getMessage());
    
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "General exception: " . $exception->getMessage()
    ]);
}
?>