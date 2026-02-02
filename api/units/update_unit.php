<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $id = $data['id'] ?? null;
        $unit_name = trim($data['unit_name'] ?? '');
        $unit_description = trim($data['unit_description'] ?? '');
        $unit_status = $data['unit_status'] ?? '';
        $division_id = isset($data['division_id']) && $data['division_id'] !== '' ? (int)$data['division_id'] : null;
    } else {
        $id = $_POST['id'] ?? null;
        $unit_name = trim($_POST['unit_name'] ?? '');
        $unit_description = trim($_POST['unit_description'] ?? '');
        $unit_status = $_POST['unit_status'] ?? '';
        $division_id = isset($_POST['division_id']) && $_POST['division_id'] !== '' ? (int)$_POST['division_id'] : null;
    }

    if (empty($id)) respond_system(400, "Unit ID is required");
    if (empty($unit_name) || empty($unit_status)) respond_system(400, "Unit name and status are required");

    // existence
    $ch = $pdo_system->prepare("SELECT id FROM units WHERE id = :id");
    $ch->execute([':id' => $id]);
    if (!$ch->fetch()) respond_system(404, "Unit not found");

    // check division exists (if provided)
    if ($division_id !== null) {
        $s = $pdo_system->prepare("SELECT id FROM divisions WHERE id = :id");
        $s->execute([':id' => $division_id]);
        if (!$s->fetch()) respond_system(404, "Assigned division not found");
    }

    // duplication per division (exclude current id)
    $dup = $pdo_system->prepare("SELECT COUNT(*) FROM units WHERE unit_name = :name AND (division_id = :div OR (:div IS NULL AND division_id IS NULL)) AND id <> :id");
    $dup->execute([':name' => $unit_name, ':div' => $division_id, ':id' => $id]);
    if ($dup->fetchColumn() > 0) {
        respond_system(409, "Another unit with same name exists in the selected division");
    }

    // update
    $sql = "UPDATE units
            SET unit_name = :name,
                unit_description = :desc,
                unit_status = :status,
                division_id = :div,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id";
    $stmt = $pdo_system->prepare($sql);
    $stmt->execute([
        ':name' => $unit_name,
        ':desc' => $unit_description,
        ':status' => $unit_status,
        ':div' => $division_id,
        ':id' => $id
    ]);

    respond_system(200, "Unit updated successfully");
} catch (Exception $e) {
    respond_system(500, "Failed to update unit", ['error' => $e->getMessage()]);
}