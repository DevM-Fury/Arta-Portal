<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond_system(405, "Invalid request method");
    }

    $officeName = "";
    $description = "";
    $status = "Active";

    // JSON body
    if (isset($_SERVER["CONTENT_TYPE"]) && stripos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input) {
            respond_system(400, "Invalid or missing JSON body");
        }
        $officeName = trim($input['office_name'] ?? '');
        $description = trim($input['description'] ?? '');
        $status      = $input['status'] ?? 'Active';
    } else {
        // form-data
        $officeName = trim($_POST['office_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status      = $_POST['status'] ?? 'Active';
    }

    if (empty($officeName)) {
        respond_system(400, "Office name is required");
    }

    // Duplication check
    $check = $pdo_system->prepare("SELECT COUNT(*) FROM offices WHERE office_name = :name");
    $check->execute([':name' => $officeName]);
    if ($check->fetchColumn() > 0) {
        respond_system(409, "Office already exists with the same name");
    }

    // Insert
    $sql = "INSERT INTO offices (office_name, description, status) VALUES (:name, :description, :status)";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':name' => $officeName,
        ':description' => $description,
        ':status' => $status
    ]);

    respond_system(200, "Office created successfully", [
        "id" => $pdo_system->lastInsertId(),
        "office_name" => $officeName,
        "description" => $description,
        "status" => $status
    ]);

} catch (Exception $e) {
    respond_system(500, "Failed to create office", ["error" => $e->getMessage()]);
}