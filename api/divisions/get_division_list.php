<?php
require_once __DIR__ . '/../../includes/config_system.php';

try {
    $stmt = $pdo_system->prepare("SELECT id, division_name FROM divisions WHERE division_status = 'Active' ORDER BY division_name ASC");
    $stmt->execute();
    $divisions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    respond_system(200, "Divisions fetched", ['divisions' => $divisions]);
} catch (Exception $e) {
    respond_system(500, "Failed to fetch divisions", ['error' => $e->getMessage()]);
}