<?php
// api/auth/register.php
require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/api_guard.php';
require_once __DIR__ . '/../../includes/config_system.php';

api_require_method('POST');
api_require_csrf();

// Get posted data
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data);

if (empty($data)) {
    http_response_code(400);
    echo json_encode([
        "status" => 400,
        "message" => "No data received."
    ]);
    exit();
}

// Required fields check
$required_fields = ['national_id', 'firstname', 'lastname', 'email', 'password', 'contact'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($data->$field)) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    http_response_code(400);
    echo json_encode([
        "status" => 400,
        "message" => "Missing required fields: " . implode(', ', $missing_fields)
    ]);
    exit();
}

try {
    // Sanitize inputs
    $national_id   = trim($data->national_id);
    $firstname     = htmlspecialchars(trim($data->firstname));
    $lastname      = htmlspecialchars(trim($data->lastname));
    $email         = filter_var(trim($data->email), FILTER_SANITIZE_EMAIL);
    $password      = $data->password;
    $contact       = trim($data->contact);
    
    // Optional foreign keys
    $office_id      = isset($data->office_id) && !empty($data->office_id) ? (int)$data->office_id : null;
    $division_id    = isset($data->division_id) && !empty($data->division_id) ? (int)$data->division_id : null;
    $unit_id        = isset($data->unit_id) && !empty($data->unit_id) ? (int)$data->unit_id : null;
    $designation_id = isset($data->designation_id) && !empty($data->designation_id) ? (int)$data->designation_id : null;
    
    $role = 'user';

    // --- Check if email already exists ---
    $check_query = "SELECT COUNT(*) FROM user_tbl WHERE email = :email";
    $check_stmt = $pdo_system->prepare($check_query);
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    
    if ($check_stmt->fetchColumn() > 0) {
        http_response_code(400);
        echo json_encode([
            "status" => 400,
            "message" => "Email already registered."
        ]);
        exit();
    }

    // --- Hash password ---
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // --- INSERT query – EXACT SCHEMA MATCH ---
    // Hindi na isinasama ang portal_id – gagamitin ang DEFAULT (uuid_generate_v4())
    // Gumagamit ng date_created at modified_date (hindi created_at/updated_at)
    $query = "INSERT INTO user_tbl (
                national_id, firstname, lastname, email, password, contact,
                office_id, division_id, unit_id, designation_id, role,
                date_created, modified_date
              ) VALUES (
                :national_id, :firstname, :lastname, :email, :password, :contact,
                :office_id, :division_id, :unit_id, :designation_id, :role,
                NOW(), NOW()
              ) RETURNING portal_id";

    $stmt = $pdo_system->prepare($query);
    
    $stmt->bindParam(':national_id', $national_id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':office_id', $office_id, $office_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindParam(':division_id', $division_id, $division_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindParam(':unit_id', $unit_id, $unit_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindParam(':designation_id', $designation_id, $designation_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        // Kunin ang auto-generated portal_id mula sa RETURNING clause
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $portal_id = $row['portal_id'];
        
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
        error_log("Registration insert failed: " . print_r($error_info, true));
        http_response_code(500);
        echo json_encode([
            "status" => 500,
            "message" => "Unable to submit registration right now."
        ]);
    }

} catch (PDOException $e) {
    error_log("PDOException in register.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Unable to submit registration right now."
    ]);
} catch (Exception $e) {
    error_log("Exception in register.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "message" => "Unable to submit registration right now."
    ]);
}
?>