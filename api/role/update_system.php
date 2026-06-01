<?php
header("Content-Type: application/json");

// ✅ Include config
require_once __DIR__ . "/../../includes/config_system.php";

// Alias $pdo_system to $pdo for backward compatibility
$pdo = $pdo_system;

// Ensure PDO exists
if (!isset($pdo)) {
    echo json_encode(["status" => "error", "message" => "Database connection (\$pdo) not initialized."]);
    exit;
}

try {
    // Determine input type: JSON or form-data
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
        $system_name = trim($data['system_name'] ?? '');
        $description = trim($data['description'] ?? '');
        $status = $data['status'] ?? '';
        $logo_path = $data['current_logo'] ?? null;
        $file_uploaded = false; // no file in JSON
    } else {
        // form-data
        $id = $_POST['id'] ?? null;
        $system_name = trim($_POST['system_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? '';
        $logo_path = $_POST['current_logo'] ?? null;
        $file_uploaded = isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK;
    }

    // Validate required fields
    if (empty($id)) {
        echo json_encode(["status" => "error", "message" => "System ID is required."]);
        exit;
    }
    if (empty($system_name) || empty($status)) {
        echo json_encode(["status" => "error", "message" => "System name and status are required."]);
        exit;
    }

    // Handle logo upload if any
    if ($file_uploaded) {
        $allowedTypes = ['image/jpeg','image/png','image/gif','image/webp'];
        if (!in_array($_FILES['logo']['type'], $allowedTypes)) {
            echo json_encode(["status" => "error", "message" => "Invalid file type. Only images allowed."]);
            exit;
        }
        if ($_FILES['logo']['size'] > 2*1024*1024) {
            echo json_encode(["status" => "error", "message" => "File too large. Max 2MB allowed."]);
            exit;
        }

        $uploadDir = SYSTEM_UPLOAD_DIR . "systems/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = uniqid() . "_" . basename($_FILES['logo']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
            $newLogoPath = "uploads/systems/" . $filename;

            // Delete old logo if exists
            if ($logo_path && file_exists(__DIR__ . "/../../" . $logo_path)) {
                unlink(__DIR__ . "/../../" . $logo_path);
            }

            $logo_path = $newLogoPath;
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to upload logo."]);
            exit;
        }
    }

    // Update database (PostgreSQL)
    $sql = "UPDATE systems 
            SET system_name = :system_name,
                description = :description,
                logo_path = :logo_path,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":system_name" => $system_name,
        ":description" => $description,
        ":logo_path" => $logo_path,
        ":status" => $status,
        ":id" => $id,
    ]);

    echo json_encode(["status" => 200, "message" => "System updated successfully"]);

} catch (Exception $e) {
    error_log('role update_system failed: ' . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Unable to update system right now."]);
}