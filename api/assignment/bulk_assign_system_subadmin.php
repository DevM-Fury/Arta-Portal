<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../../includes/config_access.php';
include_once __DIR__ . '/../../includes/api_guard.php';
include_once __DIR__ . '/../../includes/role_helper.php';

api_require_method('POST');
$currentUser = api_require_user(['admin', 'subadmin']);
api_require_csrf();
$assigned_by = $currentUser['portal_id'];

// Get POST data
$data = api_decode_json_body();
$portal_ids = $data['portal_ids'] ?? [];
$system_id = $data['system_id'] ?? '';
$role = role_normalize_name($data['role'] ?? 'user');

if (empty($portal_ids) || !$system_id) {
    echo json_encode(['status' => 400, 'title' => 'Invalid Data', 'message' => 'Portal IDs and System ID are required.']);
    exit;
}

if (!role_exists($pdo, $role)) {
    echo json_encode(['status' => 400, 'title' => 'Invalid Role', 'message' => 'Selected role does not exist.']);
    exit;
}

try {
    $success = 0;
    $fail = 0;
    $errors = [];

    foreach ($portal_ids as $portal_id) {
        // Check duplicate
        $stmt = $pdo->prepare("SELECT * FROM user_system_access WHERE portal_id = ? AND system_id = ?");
        $stmt->execute([$portal_id, $system_id]);
        if ($stmt->rowCount() > 0) {
            $fail++;
            $errors[] = "User $portal_id already has this system.";
            continue;
        }

        // Insert
        $stmt = $pdo->prepare("INSERT INTO user_system_access (portal_id, system_id, role, assigned_by, date_assigned) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$portal_id, $system_id, $role, $assigned_by]);
        $success++;
    }

    if ($success > 0) {
        echo json_encode([
            'status' => 200,
            'title' => 'Bulk Assignment Complete',
            'message' => "Assigned to $success user(s). $fail failed."
        ]);
    } else {
        echo json_encode([
            'status' => 400,
            'title' => 'Bulk Assignment Failed',
            'message' => 'No assignments were successful. ' . implode(' ', $errors)
        ]);
    }

} catch (PDOException $e) {
    api_json_response(500, [
        'status' => 500,
        'title' => 'Database Error',
        'message' => 'Unable to complete bulk assignment right now.'
    ]);
}
?>