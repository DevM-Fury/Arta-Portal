<?php
function log_action($pdo, $portal_id, $assignment_id = null, $category, $action, $accountable = null) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO log_tbl (portal_id, assignment_id, category, action, accountable, date_created)
            VALUES (:portal_id, :assignment_id, :category, :action, :accountable, NOW())
        ");
        $stmt->execute([
            ':portal_id' => $portal_id,
            ':assignment_id' => $assignment_id,
            ':category' => $category,
            ':action' => $action,
            ':accountable' => $accountable
        ]);
    } catch (PDOException $e) {
        error_log('Log insert failed: ' . $e->getMessage());
    }
}
?>