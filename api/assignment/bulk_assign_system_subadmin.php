<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../../includes/config_access.php';

// Check subadmin login
$assigned_by = $_SESSION['user']['portal_id'] ?? null;
if (!$assigned_by) {
    echo json_encode(['status' => 401, 'title' => 'Unauthorized', 'message' => 'Please log in as admin.']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$portal_ids = $data['portal_ids'] ?? [];
$system_id = $data['system_id'] ?? '';
$role = $data['role'] ?? 'user';

if (empty($portal_ids) || !$system_id) {
    echo json_encode(['status' => 400, 'title' => 'Invalid Data', 'message' => 'Portal IDs and System ID are required.']);
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
    echo json_encode([
        'status' => 500,
        'title' => 'Database Error',
        'message' => $e->getMessage()
    ]);
}
?>