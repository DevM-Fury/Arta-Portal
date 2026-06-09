<?php
header("Content-Type: application/json");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Include config
require_once __DIR__ . "/../../includes/config_system.php";
require_once __DIR__ . '/../../includes/log_helper.php';

// Alias PDO
$pdo = $pdo_system;

// Determine input type
if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;
} else {
    $id = $_POST['id'] ?? null;
}

// Validate
if (empty($id)) {
    echo json_encode(["status" => "error", "message" => "System ID is required."]);
    exit;
}

try {
    // Check if system exists
    $stmt = $pdo->prepare("SELECT system_name, domain_url, logo_path FROM systems WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $system = $stmt->fetch();

    if (!$system) {
        echo json_encode(["status" => "error", "message" => "System not found."]);
        exit;
    }

    // Delete system from database
    $stmt = $pdo->prepare("DELETE FROM systems WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // Delete logo file if exists
    if (!empty($system['logo_path']) && file_exists(__DIR__ . "/../../" . $system['logo_path'])) {
        unlink(__DIR__ . "/../../" . $system['logo_path']);
    }

    if (!empty($_SESSION['user']['portal_id'])) {
        log_transaction(
            $pdo,
            $_SESSION['user']['portal_id'],
            'DELETE_SYSTEM',
            'systems',
            $id,
            'Deleted a system record.',
            [
                'system_name' => $system['system_name'] ?? null,
                'domain_url' => $system['domain_url'] ?? null,
            ],
            $_SESSION['user']['role'] ?? null
        );
    }

    echo json_encode(["status" => 200, "message" => "System deleted successfully"]);

} catch (Exception $e) {
    error_log('systems delete_system failed: ' . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Unable to delete system right now."]);
}