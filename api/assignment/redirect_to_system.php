<?php
// ========================================
// redirect_to_system.php
// ========================================

// ✅ JSON headers and session start
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../../includes/config_access.php'; // PostgreSQL PDO connection
require_once __DIR__ . '/../../includes/log_helper.php';
require_once __DIR__ . '/../../includes/sso_helper.php';

$ssoConfig = sso_get_config();

// ✅ Check if user is logged in
if (!isset($_SESSION['user']['portal_id'])) {
	http_response_code(401);
	echo json_encode(['status' => 401, 'message' => 'Not logged in']);
	exit;
}

// ✅ Get user and target system
$portal_id = $_SESSION['user']['portal_id'];
$domain_url = $_GET['domain_url'] ?? '';

if (empty($domain_url)) {
	http_response_code(400);
	echo json_encode(['status' => 400, 'message' => 'Missing domain_url parameter']);
	exit;
}

try {
	$systemStmt = $pdo->prepare('SELECT id, system_name, domain_url FROM systems WHERE domain_url = :domain_url LIMIT 1');
	$systemStmt->execute([':domain_url' => $domain_url]);
	$system = $systemStmt->fetch(PDO::FETCH_ASSOC) ?: null;

	log_system_event(
		$pdo,
		$portal_id,
		'SYSTEM_ACCESS_REQUESTED',
		'System access requested' . ($system ? ' for ' . $system['system_name'] : ''),
		[
			'system_id' => $system['id'] ?? null,
			'system_name' => $system['system_name'] ?? null,
			'domain_url' => $domain_url,
		]
	);

	$systemContext = $system ?: ['system_name' => '', 'domain_url' => $domain_url];
	$system_url = sso_resolve_system_base_url($systemContext, $ssoConfig);
	if (!sso_is_allowed_redirect_url($system_url, $ssoConfig, $pdo)) {
		http_response_code(400);
		echo json_encode(['status' => 400, 'message' => 'Resolved system URL is not allowed']);
		exit;
	}
	
	// ✅ Generate a secure token (SSO)
	$token = bin2hex(random_bytes(32)); // 64-character secure token
	$redirect_url = sso_build_login_url($system_url, $token);
	if ($redirect_url === null) {
		http_response_code(400);
		echo json_encode(['status' => 400, 'message' => 'Resolved system URL is invalid']);
		exit;
	}

	$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

	// ✅ Insert token into sso_tokens table
	$stmt = $pdo->prepare("
		INSERT INTO sso_tokens (portal_id, token, expires_at)
		VALUES (:portal_id, :token, :expires_at)
	");
	$stmt->execute([
		':portal_id' => $portal_id,
		':token' => $token,
		':expires_at' => $expires_at
	]);

	log_system_event(
		$pdo,
		$portal_id,
		'SYSTEM_REDIRECT_DISPATCHED',
		'Redirect sent to external system' . ($system ? ' ' . $system['system_name'] : ''),
		[
			'system_id' => $system['id'] ?? null,
			'system_name' => $system['system_name'] ?? null,
			'domain_url' => $domain_url,
			'redirect_url' => $redirect_url,
		]
	);

	header("Location: $redirect_url");
	exit;

} catch (PDOException $e) {
	http_response_code(500);
	echo json_encode([
		'status' => 500,
		'message' => 'Database error during redirect.',
		'error' => $e->getMessage()
	]);
	exit;
}
