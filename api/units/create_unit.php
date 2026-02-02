<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond_system(405, "Invalid request method");
    }

    // JSON or form-data
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $unit_name = trim($input['unit_name'] ?? '');
        $unit_description = trim($input['unit_description'] ?? '');
        $unit_status = $input['unit_status'] ?? 'Active';
        $division_id = isset($input['division_id']) && $input['division_id'] !== '' ? (int)$input['division_id'] : null;
    } else {
        $unit_name = trim($_POST['unit_name'] ?? '');
        $unit_description = trim($_POST['unit_description'] ?? '');
        $unit_status = $_POST['unit_status'] ?? 'Active';
        $division_id = isset($_POST['division_id']) && $_POST['division_id'] !== '' ? (int)$_POST['division_id'] : null;
    }

    if (empty($unit_name)) {
        respond_system(400, "Unit name is required");
    }

    // If division_id provided, ensure it exists
    if ($division_id !== null) {
        $chk = $pdo_system->prepare("SELECT id FROM divisions WHERE id = :id");
        $chk->execute([':id' => $division_id]);
        if (!$chk->fetch()) respond_system(404, "Assigned division not found");
    }

    // Duplication check per division (if you used unique index)
    $dup = $pdo_system->prepare("SELECT COUNT(*) FROM units WHERE unit_name = :name AND (division_id = :div OR (:div IS NULL AND division_id IS NULL))");
    $dup->execute([':name' => $unit_name, ':div' => $division_id]);
    if ($dup->fetchColumn() > 0) {
        respond_system(409, "Unit already exists in the selected division");
    }

    // Insert (Postgres RETURNING)
    $sql = "INSERT INTO units (unit_name, unit_description, unit_status, division_id)
            VALUES (:name, :desc, :status, :div)
            RETURNING id, created_at, updated_at";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':name' => $unit_name,
        ':desc' => $unit_description,
        ':status' => $unit_status,
        ':div' => $division_id
    ]);
    $inserted = $stmt->fetch(PDO::FETCH_ASSOC);

    respond_system(200, "Unit created successfully", [
        'unit' => array_merge($inserted, [
            'unit_name' => $unit_name,
            'unit_description' => $unit_description,
            'unit_status' => $unit_status,
            'division_id' => $division_id
        ])
    ]);
} catch (Exception $e) {
    respond_system(500, "Failed to create unit", ['error' => $e->getMessage()]);
}