<?php
require_once __DIR__ . '/../../includes/config_system.php';
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['portal_id'])) respond_system(400, 'Missing user portal_id');

try {
  $stmt = $pdo_system->prepare("
    UPDATE user_tbl
    SET office_id = :office,
        division_id = :division,
        unit_id = :unit,
        designation_id = :designation,
        modified_date = NOW()
    WHERE portal_id = :id
  ");
  $stmt->execute([
    'office' => $data['office_id'],
    'division' => $data['division_id'],
    'unit' => $data['unit_id'],
    'designation' => $data['designation_id'],
    'id' => $data['portal_id']
  ]);
  respond_system(200, 'User assignment updated successfully');
} catch (Exception $e) {
  respond_system(500, 'Failed to update assignment', $e->getMessage());
}