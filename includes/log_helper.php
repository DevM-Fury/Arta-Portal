<?php

function log_client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

function log_client_user_agent(): string
{
    return substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'), 0, 255);
}

function log_table_columns(PDO $pdo, string $tableName): array
{
    static $cache = [];

    if (isset($cache[$tableName])) {
        return $cache[$tableName];
    }

    $stmt = $pdo->prepare(
        "SELECT column_name, is_nullable
         FROM information_schema.columns
         WHERE table_schema = 'public' AND table_name = :table_name"
    );
    $stmt->execute([':table_name' => $tableName]);

    $columns = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [] as $row) {
        $columnName = (string) ($row['column_name'] ?? '');
        if ($columnName === '') {
            continue;
        }

        $columns[$columnName] = [
            'nullable' => strtoupper((string) ($row['is_nullable'] ?? 'YES')) === 'YES',
        ];
    }

    $cache[$tableName] = $columns;
    return $columns;
}

function log_action($pdo, $portal_id, $assignment_id = null, $category = null, $action = null, $accountable = null): void
{
    try {
        $columns = log_table_columns($pdo, 'log_tbl');
        if ($columns === []) {
            return;
        }

        if (isset($columns['details'])) {
            $details = trim((string) $accountable);
            if ($assignment_id !== null && $assignment_id !== '') {
                $details = trim('assignment_id=' . $assignment_id . ' ' . $details);
            }

            $stmt = $pdo->prepare(
                'INSERT INTO log_tbl (portal_id, action, details, ip_address, device_info) VALUES (:portal_id, :action, :details, :ip_address, :device_info)'
            );
            $stmt->execute([
                ':portal_id' => $portal_id,
                ':action' => $action,
                ':details' => $details,
                ':ip_address' => log_client_ip(),
                ':device_info' => log_client_user_agent(),
            ]);
            return;
        }

        if (isset($columns['category']) && isset($columns['accountable'])) {
            $stmt = $pdo->prepare(
                'INSERT INTO log_tbl (portal_id, assignment_id, category, action, accountable, date_created) VALUES (:portal_id, :assignment_id, :category, :action, :accountable, NOW())'
            );
            $stmt->execute([
                ':portal_id' => $portal_id,
                ':assignment_id' => $assignment_id,
                ':category' => $category,
                ':action' => $action,
                ':accountable' => $accountable,
            ]);
        }
    } catch (PDOException $e) {
        error_log('Log insert failed: ' . $e->getMessage());
    }
}

function log_system_event(PDO $pdo, $portalId, string $action, string $details, array $context = []): void
{
    $summary = $details;
    if ($context !== []) {
        $contextText = json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($contextText !== false) {
            $summary .= ' | context=' . $contextText;
        }
    }

    log_action(
        $pdo,
        $portalId,
        null,
        'system_access',
        $action,
        substr($summary, 0, 1000)
    );
}

function ensure_transaction_logs_table(PDO $pdo): void
{
    static $ready = false;

    if ($ready) {
        return;
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS public.transaction_logs (
            id BIGSERIAL PRIMARY KEY,
            portal_id VARCHAR(100) NOT NULL,
            actor_role VARCHAR(50),
            action VARCHAR(100) NOT NULL,
            entity_type VARCHAR(100) NOT NULL,
            entity_id VARCHAR(100),
            description TEXT NOT NULL,
            context JSONB,
            ip_address VARCHAR(64),
            user_agent VARCHAR(255),
            created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $pdo->exec(
        'CREATE INDEX IF NOT EXISTS idx_transaction_logs_created_at ON public.transaction_logs (created_at DESC)'
    );
    $pdo->exec(
        'CREATE INDEX IF NOT EXISTS idx_transaction_logs_portal_id ON public.transaction_logs (portal_id)'
    );

    $ready = true;
}

function log_transaction(PDO $pdo, $portalId, string $action, string $entityType, $entityId, string $description, array $context = [], ?string $actorRole = null): void
{
    try {
        ensure_transaction_logs_table($pdo);

        $stmt = $pdo->prepare(
            'INSERT INTO public.transaction_logs (portal_id, actor_role, action, entity_type, entity_id, description, context, ip_address, user_agent)
             VALUES (:portal_id, :actor_role, :action, :entity_type, :entity_id, :description, CAST(:context AS jsonb), :ip_address, :user_agent)'
        );

        $contextJson = $context === []
            ? '{}'
            : (json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}');

        $stmt->execute([
            ':portal_id' => (string) $portalId,
            ':actor_role' => $actorRole,
            ':action' => $action,
            ':entity_type' => $entityType,
            ':entity_id' => $entityId === null ? null : (string) $entityId,
            ':description' => $description,
            ':context' => $contextJson,
            ':ip_address' => log_client_ip(),
            ':user_agent' => log_client_user_agent(),
        ]);
    } catch (Throwable $e) {
        error_log('Transaction log insert failed: ' . $e->getMessage());
    }
}

?>