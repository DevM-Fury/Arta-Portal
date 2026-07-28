<?php
ob_clean();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

include_once __DIR__ . '/../../includes/config_access.php';

if (empty($_SESSION['user']['portal_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in.']);
    exit;
}

$portalId = (string) $_SESSION['user']['portal_id'];

try {
    $columnStmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'user_tbl'");
    $availableColumns = $columnStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    $availableColumns = array_map('strtolower', $availableColumns);

    $hasColumn = static function (string $columnName) use ($availableColumns): bool {
        return in_array(strtolower($columnName), $availableColumns, true);
    };

    $selectColumns = [
        'u.portal_id',
        'u.national_id',
        'u.firstname',
        'u.lastname',
        $hasColumn('middlename') ? 'u.middlename' : "NULL AS middlename",
        $hasColumn('birthdate') ? "TO_CHAR(u.birthdate, 'Month DD, YYYY') AS birthdate" : "NULL AS birthdate",
        $hasColumn('address') ? 'u.address' : "NULL AS address",
        $hasColumn('contact') ? 'u.contact' : "NULL AS contact",
        'u.email',
        'u.role',
        $hasColumn('office_location') ? 'u.office_location' : "NULL AS office_location",
        $hasColumn('office_id') ? 'u.office_id' : "NULL AS office_id",
        $hasColumn('division_id') ? 'u.division_id' : "NULL AS division_id",
        $hasColumn('unit_id') ? 'u.unit_id' : "NULL AS unit_id",
        $hasColumn('designation_id') ? 'u.designation_id' : "NULL AS designation_id",
        "COALESCE(o.office_name, 'N/A') AS office_name",
        "COALESCE(d.division_name, 'N/A') AS division_name",
        "COALESCE(un.unit_name, 'N/A') AS unit_name",
        "COALESCE(desig.designation_name, 'N/A') AS designation_name",
        $hasColumn('date_created') ? "TO_CHAR(u.date_created, 'Month DD, YYYY') AS date_created" : "NULL AS date_created",
        $hasColumn('modified_date') ? "TO_CHAR(u.modified_date, 'Month DD, YYYY HH12:MI AM') AS last_update" : "NULL AS last_update",
    ];

    $sql = "
        SELECT
            " . implode(",\n            ", $selectColumns) . "
        FROM user_tbl u
        LEFT JOIN offices o ON u.office_id = o.id
        LEFT JOIN divisions d ON u.division_id = d.id
        LEFT JOIN units un ON u.unit_id = un.id
        LEFT JOIN designations desig ON u.designation_id = desig.id
        WHERE u.portal_id = :portal_id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':portal_id' => $portalId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

    echo json_encode(['status' => 'success', 'user' => $user]);
} catch (Throwable $e) {
    error_log('get_profile.php error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Internal server error.']);
}