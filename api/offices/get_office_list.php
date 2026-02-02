<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    $stmt = $pdo_system->prepare("SELECT id, office_name FROM offices WHERE status = 'Active' ORDER BY office_name ASC");
    $stmt->execute();
    $offices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    respond_system(200, "Offices fetched", ['offices' => $offices]);
} catch (Exception $e) {
    respond_system(500, "Failed to fetch offices", ['error' => $e->getMessage()]);
}