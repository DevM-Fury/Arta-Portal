<?php
// api/users/update_user.php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) respond_system(400, 'Invalid JSON');

    $portal_id = $payload['portal_id'] ?? '';
    if (!$portal_id) respond_system(400, 'Missing portal_id');

    // check exists and get current data
    $stmt = $pdo_system->prepare("SELECT portal_id, email, national_id FROM user_tbl WHERE portal_id = :id LIMIT 1");
    $stmt->execute([':id' => $portal_id]);
    $user = $stmt->fetch();
    if (!$user) respond_system(404, 'User not found');

    $fields = [];
    $params = [':id' => $portal_id];

    // Validate and update National ID
    if (isset($payload['national_id'])) {
        $national_id = trim($payload['national_id']);
        if (empty($national_id)) {
            respond_system(400, 'National ID cannot be empty');
        }
        // Check if national_id format is valid (adjust regex based on your country's format)
        if (!preg_match('/^[0-9]{9,12}$/', $national_id)) {
            respond_system(400, 'Invalid National ID format. Must be 9-12 digits');
        }
        // Check uniqueness (except current user)
        $chk = $pdo_system->prepare("SELECT portal_id FROM user_tbl WHERE national_id = :national_id AND portal_id != :id LIMIT 1");
        $chk->execute([':national_id' => $national_id, ':id' => $portal_id]);
        if ($chk->fetch()) {
            respond_system(409, 'National ID already exists in system');
        }
        $fields[] = "national_id = :national_id";
        $params[':national_id'] = $national_id;
    }

    // Validate and update Firstname
    if (isset($payload['firstname'])) {
        $firstname = trim($payload['firstname']);
        if (empty($firstname)) {
            respond_system(400, 'First name cannot be empty');
        }
        if (strlen($firstname) < 2 || strlen($firstname) > 50) {
            respond_system(400, 'First name must be between 2 and 50 characters');
        }
        if (!preg_match('/^[a-zA-Z\s\'-]+$/', $firstname)) {
            respond_system(400, 'First name contains invalid characters');
        }
        $fields[] = "firstname = :firstname";
        $params[':firstname'] = $firstname;
    }

    // Validate and update Lastname
    if (isset($payload['lastname'])) {
        $lastname = trim($payload['lastname']);
        if (empty($lastname)) {
            respond_system(400, 'Last name cannot be empty');
        }
        if (strlen($lastname) < 2 || strlen($lastname) > 50) {
            respond_system(400, 'Last name must be between 2 and 50 characters');
        }
        if (!preg_match('/^[a-zA-Z\s\'-]+$/', $lastname)) {
            respond_system(400, 'Last name contains invalid characters');
        }
        $fields[] = "lastname = :lastname";
        $params[':lastname'] = $lastname;
    }

    // Validate and update Contact
    if (isset($payload['contact'])) {
        $contact = trim($payload['contact']);
        if (empty($contact)) {
            respond_system(400, 'Contact number cannot be empty');
        }
        // Philippine mobile number format (adjust based on your country)
        if (!preg_match('/^(09|\+639)\d{9}$/', $contact)) {
            respond_system(400, 'Invalid contact number. Must be a valid mobile number (e.g., 09xxxxxxxxx or +639xxxxxxxxx)');
        }
        $fields[] = "contact = :contact";
        $params[':contact'] = $contact;
    }

    // Validate and update Email
    if (isset($payload['email'])) {
        $email = trim($payload['email']);
        if (empty($email)) {
            respond_system(400, 'Email cannot be empty');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            respond_system(400, 'Invalid email format');
        }
        // Check if email domain is allowed (optional)
        $allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'company.com.ph']; // Add your domains
        $domain = substr(strrchr($email, "@"), 1);
        if (!in_array($domain, $allowed_domains)) {
            // Comment this out if you don't want to restrict domains
            // respond_system(400, 'Email domain not allowed');
        }
        // Check uniqueness if email changed
        if ($email !== $user['email']) {
            $chk = $pdo_system->prepare("SELECT portal_id FROM user_tbl WHERE email = :email AND portal_id != :id LIMIT 1");
            $chk->execute([':email' => $email, ':id' => $portal_id]);
            if ($chk->fetch()) {
                respond_system(409, 'Email already in use by another user');
            }
        }
        $fields[] = "email = :email";
        $params[':email'] = $email;
    }

    // Validate and update Role
    if (isset($payload['role'])) {
        $role = strtolower(trim($payload['role']));
        $allowed_roles = ['admin', 'subadmin', 'user'];
        if (!in_array($role, $allowed_roles)) {
            respond_system(400, 'Invalid role. Allowed roles: admin, subadmin, user');
        }
        $fields[] = "role = :role";
        $params[':role'] = $role;
    }

    // Validate and update Status
    if (isset($payload['status'])) {
        $status = strtolower(trim($payload['status']));
        $allowed_statuses = ['active', 'inactive', 'suspended'];
        if (!in_array($status, $allowed_statuses)) {
            respond_system(400, 'Invalid status. Allowed statuses: active, inactive, suspended');
        }
        $fields[] = "status = :status";
        $params[':status'] = $status;
    }

    // Validate and update Password
    if (!empty($payload['password'])) {
        $password = $payload['password'];
        
        // Password strength validation
        if (strlen($password) < 8) {
            respond_system(400, 'Password must be at least 8 characters long');
        }
        if (!preg_match('/[A-Z]/', $password)) {
            respond_system(400, 'Password must contain at least one uppercase letter');
        }
        if (!preg_match('/[a-z]/', $password)) {
            respond_system(400, 'Password must contain at least one lowercase letter');
        }
        if (!preg_match('/[0-9]/', $password)) {
            respond_system(400, 'Password must contain at least one number');
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            respond_system(400, 'Password must contain at least one special character');
        }
        
        $fields[] = "password = :password";
        $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    if (empty($fields)) {
        respond_system(400, 'No fields to update');
    }

    // Always update modified_date
    $fields[] = "modified_date = NOW()";

    $sql = "UPDATE user_tbl SET " . implode(', ', $fields) . " WHERE portal_id = :id";
    $upd = $pdo_system->prepare($sql);
    $upd->execute($params);

    // Get updated user data for response
    $stmt = $pdo_system->prepare("SELECT portal_id, national_id, firstname, lastname, email, contact, role, status, date_created, modified_date FROM user_tbl WHERE portal_id = :id");
    $stmt->execute([':id' => $portal_id]);
    $updated_user = $stmt->fetch(PDO::FETCH_ASSOC);

    respond_system(200, 'User updated successfully', $updated_user);

} catch (PDOException $e) {
    error_log("update_user error: " . $e->getMessage());
    respond_system(500, 'Server error: ' . $e->getMessage());
} catch (Exception $e) {
    error_log("update_user error: " . $e->getMessage());
    respond_system(500, 'Server error: ' . $e->getMessage());
}
?>