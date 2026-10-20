<?php

require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/api_guard.php';

api_require_method('POST');
$currentUser = api_require_user();
api_require_csrf();

$payload = api_decode_json_body();
$portalId = (string) ($currentUser['portal_id'] ?? '');

try {
    $columnStmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'user_tbl'");
    $availableColumns = $columnStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    $availableColumns = array_map('strtolower', $availableColumns);

    $hasColumn = static function (string $columnName) use ($availableColumns): bool {
        return in_array(strtolower($columnName), $availableColumns, true);
    };

    $updates = [];
    $params = [':portal_id' => $portalId];

    $setString = static function (string $payloadKey, string $columnName) use (&$payload, &$updates, &$params, $hasColumn): void {
        if (!array_key_exists($payloadKey, $payload) || !$hasColumn($columnName)) {
            return;
        }

        $value = trim((string) $payload[$payloadKey]);
        $updates[] = $columnName . ' = :' . $payloadKey;
        $params[':' . $payloadKey] = $value;
    };

    $setNullableInt = static function (string $payloadKey, string $columnName) use (&$payload, &$updates, &$params, $hasColumn): void {
        if (!array_key_exists($payloadKey, $payload) || !$hasColumn($columnName)) {
            return;
        }

        $value = $payload[$payloadKey];
        if ($value === '' || $value === null) {
            $updates[] = $columnName . ' = :' . $payloadKey;
            $params[':' . $payloadKey] = null;
            return;
        }

        if (!is_numeric($value) || (int) $value <= 0) {
            api_json_response(422, [
                'status' => 422,
                'title' => 'Invalid selection',
                'message' => 'One or more assignment fields contain an invalid value.',
            ]);
        }

        $updates[] = $columnName . ' = :' . $payloadKey;
        $params[':' . $payloadKey] = (int) $value;
    };

    if (array_key_exists('firstname', $payload) && $hasColumn('firstname')) {
        $firstname = trim((string) $payload['firstname']);
        if ($firstname === '') {
            api_json_response(422, [
                'status' => 422,
                'title' => 'Missing first name',
                'message' => 'First name is required.',
            ]);
        }

        $updates[] = 'firstname = :firstname';
        $params[':firstname'] = $firstname;
    }

    if (array_key_exists('lastname', $payload) && $hasColumn('lastname')) {
        $lastname = trim((string) $payload['lastname']);
        if ($lastname === '') {
            api_json_response(422, [
                'status' => 422,
                'title' => 'Missing last name',
                'message' => 'Last name is required.',
            ]);
        }

        $updates[] = 'lastname = :lastname';
        $params[':lastname'] = $lastname;
    }

    $setString('middlename', 'middlename');
    $setString('address', 'address');
    $setString('office_location', 'office_location');
    $setString('contact', 'contact');

    if (array_key_exists('national_id', $payload) && $hasColumn('national_id')) {
        $nationalId = preg_replace('/\D+/', '', (string) $payload['national_id']);

        if ($nationalId === '' || !preg_match('/^[0-9]{9,12}$/', $nationalId)) {
            api_json_response(422, [
                'status' => 422,
                'title' => 'Invalid National ID',
                'message' => 'National ID must contain 9 to 12 digits.',
            ]);
        }

        $checkNationalId = $pdo->prepare('SELECT portal_id FROM user_tbl WHERE national_id = :national_id AND portal_id != :portal_id LIMIT 1');
        $checkNationalId->execute([
            ':national_id' => $nationalId,
            ':portal_id' => $portalId,
        ]);

        if ($checkNationalId->fetchColumn()) {
            api_json_response(409, [
                'status' => 409,
                'title' => 'National ID already used',
                'message' => 'That National ID is already assigned to another account.',
            ]);
        }

        $updates[] = 'national_id = :national_id';
        $params[':national_id'] = $nationalId;
    }

    if (array_key_exists('email', $payload) && $hasColumn('email')) {
        $email = filter_var(trim((string) $payload['email']), FILTER_VALIDATE_EMAIL);

        if ($email === false) {
            api_json_response(422, [
                'status' => 422,
                'title' => 'Invalid email',
                'message' => 'Please enter a valid email address.',
            ]);
        }

        $checkEmail = $pdo->prepare('SELECT portal_id FROM user_tbl WHERE email = :email AND portal_id != :portal_id LIMIT 1');
        $checkEmail->execute([
            ':email' => $email,
            ':portal_id' => $portalId,
        ]);

        if ($checkEmail->fetchColumn()) {
            api_json_response(409, [
                'status' => 409,
                'title' => 'Email already used',
                'message' => 'That email address is already assigned to another account.',
            ]);
        }

        $updates[] = 'email = :email';
        $params[':email'] = $email;
    }

    $setNullableInt('office_id', 'office_id');
    $setNullableInt('division_id', 'division_id');
    $setNullableInt('unit_id', 'unit_id');
    $setNullableInt('designation_id', 'designation_id');

    if ($updates === []) {
        api_json_response(200, [
            'status' => 200,
            'message' => 'No profile changes were submitted.',
        ]);
    }

    if ($hasColumn('modified_date')) {
        $updates[] = 'modified_date = NOW()';
    }

    $sql = 'UPDATE user_tbl SET ' . implode(', ', $updates) . ' WHERE portal_id = :portal_id';
    $stmt = $pdo->prepare($sql);

    foreach ($params as $param => $value) {
        if ($value === null) {
            $stmt->bindValue($param, null, PDO::PARAM_NULL);
            continue;
        }

        if (is_int($value)) {
            $stmt->bindValue($param, $value, PDO::PARAM_INT);
            continue;
        }

        $stmt->bindValue($param, $value, PDO::PARAM_STR);
    }

    $stmt->execute();

    if (isset($params[':firstname'])) {
        $_SESSION['user']['firstname'] = $params[':firstname'];
    }

    if (isset($params[':lastname'])) {
        $_SESSION['user']['lastname'] = $params[':lastname'];
    }

    if (isset($params[':email'])) {
        $_SESSION['user']['email'] = $params[':email'];
    }

    api_json_response(200, [
        'status' => 200,
        'message' => 'Profile updated successfully.',
    ]);
} catch (Throwable $exception) {
    error_log('update_profile.php error: ' . $exception->getMessage());

    api_json_response(500, [
        'status' => 500,
        'title' => 'Update failed',
        'message' => 'Unable to update profile right now.',
    ]);
}