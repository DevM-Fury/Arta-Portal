<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond_system(405, "Invalid request method");
    }

    // Accept form-data (from your frontend FormData)
    $name = trim($_POST['designation_name'] ?? '');
    $desc = trim($_POST['designation_description'] ?? '');
    $status = $_POST['designation_status'] ?? 'Active';

    if ($name === '') {
        respond_system(400, "Designation name is required");
    }

    $sql = "INSERT INTO designations (designation_name, designation_description, designation_status)
            VALUES (:name, :desc, :status)
            RETURNING id, created_at, updated_at";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':desc' => $desc,
        ':status' => $status
    ]);

    $inserted = $stmt->fetch(PDO::FETCH_ASSOC);

    respond_system(200, "Designation created successfully", [
        'id' => $inserted['id'],
        'created_at' => $inserted['created_at'],
        'updated_at' => $inserted['updated_at'],
        'designation_name' => $name,
        'designation_description' => $desc,
        'designation_status' => $status
    ]);

} catch (Exception $e) {
    respond_system(500, "Failed to create designation", ['error' => $e->getMessage()]);
}