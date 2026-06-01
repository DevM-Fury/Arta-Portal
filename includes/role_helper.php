<?php

function role_default_seed_data(): array
{
    return [
        ['role_name' => 'user', 'role_level' => 10],
        ['role_name' => 'property', 'role_level' => 20],
        ['role_name' => 'inspector', 'role_level' => 30],
        ['role_name' => 'subadmin', 'role_level' => 40],
        ['role_name' => 'admin', 'role_level' => 50],
        ['role_name' => 'auditor', 'role_level' => 60],
        ['role_name' => 'manager', 'role_level' => 70],
        ['role_name' => 'end_user', 'role_level' => 80],
    ];
}

function role_normalize_name(string $roleName): string
{
    $normalized = strtolower(trim($roleName));
    $normalized = preg_replace('/\s+/', '_', $normalized);
    return preg_replace('/[^a-z0-9_]/', '', $normalized);
}

function role_seed_defaults(PDO $pdo): void
{
    static $seeded = false;

    if ($seeded) {
        return;
    }

    $count = (int) $pdo->query("SELECT COUNT(*) FROM roles_tbl")->fetchColumn();
    if ($count === 0) {
        $stmt = $pdo->prepare("INSERT INTO roles_tbl (role_name, role_level) VALUES (:role_name, :role_level)");
        foreach (role_default_seed_data() as $role) {
            $stmt->execute([
                ':role_name' => $role['role_name'],
                ':role_level' => $role['role_level'],
            ]);
        }
    }

    $seeded = true;
}

function role_fetch_all(PDO $pdo): array
{
    role_seed_defaults($pdo);

    $stmt = $pdo->query("SELECT role_id, role_name, role_level FROM roles_tbl ORDER BY role_level ASC, role_name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function role_exists(PDO $pdo, string $roleName): bool
{
    role_seed_defaults($pdo);

    $normalized = role_normalize_name($roleName);
    if ($normalized === '') {
        return false;
    }

    $stmt = $pdo->prepare("SELECT 1 FROM roles_tbl WHERE LOWER(role_name) = :role_name LIMIT 1");
    $stmt->execute([':role_name' => $normalized]);
    return (bool) $stmt->fetchColumn();
}

function role_format_label(string $roleName): string
{
    return ucwords(str_replace('_', ' ', $roleName));
}
