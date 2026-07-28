<?php

function sso_parse_csv_env($value) {
	if (!is_string($value) || trim($value) === '') {
		return [];
	}

	$items = array_map('trim', explode(',', $value));
	$items = array_filter($items, static function ($item) {
		return $item !== '';
	});

	return array_values(array_unique($items));
}

function sso_get_config() {
	static $config = null;

	if ($config !== null) {
		return $config;
	}

	$portalBaseUrl = rtrim((string) (getenv('PORTAL_BASE_URL') ?: ''), '/');
	$amsBaseUrl = rtrim((string) (getenv('AMS_BASE_URL') ?: ''), '/');
	$helpdeskBaseUrl = rtrim((string) (getenv('HELPDESK_BASE_URL') ?: ''), '/');
	$ttlMinutes = (int) (getenv('SSO_TOKEN_TTL_MINUTES') ?: 15);

	$allowedDomains = sso_parse_csv_env((string) getenv('ALLOWED_SSO_DOMAINS'));
	foreach ([$amsBaseUrl, $helpdeskBaseUrl] as $baseUrl) {
		if ($baseUrl !== '') {
			$baseHost = sso_extract_host($baseUrl);
			if ($baseHost) {
				$allowedDomains[] = $baseHost;
			}
		}
	}

	$allowedOrigins = [];
	foreach ([$portalBaseUrl, $amsBaseUrl, (string) (getenv('ALLOWED_ORIGIN') ?: '')] as $origin) {
		$origin = trim($origin);
		if ($origin !== '') {
			$allowedOrigins[] = rtrim($origin, '/');
		}
	}

	$config = [
		'portal_base_url' => $portalBaseUrl,
		'ams_base_url' => $amsBaseUrl,
		'helpdesk_base_url' => $helpdeskBaseUrl,
		'ttl_minutes' => $ttlMinutes > 0 ? $ttlMinutes : 15,
		'allowed_domains' => array_values(array_unique($allowedDomains)),
		'allowed_origins' => array_values(array_unique($allowedOrigins)),
		'allowed_roles' => ['admin', 'user', 'inspector', 'property', 'manager', 'subadmin', 'end_user', 'auditor', 'ict'],
		'production' => in_array(strtolower((string) getenv('APP_ENV')), ['prod', 'production'], true),
	];

	return $config;
}

function sso_extract_host($url) {
	if (!is_string($url) || trim($url) === '') {
		return null;
	}

	$parts = parse_url($url);
	if (!$parts || empty($parts['host'])) {
		return null;
	}

	return strtolower($parts['host']);
}

function sso_is_safe_url($url) {
	if (!is_string($url) || trim($url) === '') {
		return false;
	}

	$parts = parse_url($url);
	if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
		return false;
	}

	$scheme = strtolower($parts['scheme']);
	if (!in_array($scheme, ['http', 'https'], true)) {
		return false;
	}

	if (!empty($parts['user']) || !empty($parts['pass'])) {
		return false;
	}

	return true;
}

function sso_host_matches($host, $allowedHost) {
	$host = strtolower(trim((string) $host));
	$allowedHost = strtolower(trim((string) $allowedHost));

	if ($host === '' || $allowedHost === '') {
		return false;
	}

	if ($host === $allowedHost) {
		return true;
	}

	return substr($host, -strlen('.' . $allowedHost)) === '.' . $allowedHost;
}

function sso_is_allowed_domain($url, array $config) {
	if (!sso_is_safe_url($url)) {
		return false;
	}

	$host = sso_extract_host($url);
	if (!$host) {
		return false;
	}

	if (empty($config['allowed_domains'])) {
		return true;
	}

	foreach ($config['allowed_domains'] as $allowedHost) {
		if (sso_host_matches($host, $allowedHost)) {
			return true;
		}
	}

	return false;
}

function sso_get_registered_system_hosts($pdo) {
	static $hosts = null;

	if ($hosts !== null) {
		return $hosts;
	}

	$hosts = [];

	try {
		$stmt = $pdo->query(
			"SELECT domain_url FROM systems WHERE NULLIF(TRIM(domain_url), '') IS NOT NULL AND LOWER(COALESCE(status, '')) = 'active'"
		);
		$urls = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

		foreach ($urls as $url) {
			if (!sso_is_safe_url($url)) {
				continue;
			}

			$host = sso_extract_host($url);
			if ($host) {
				$hosts[] = $host;
			}
		}
	} catch (Throwable $exception) {
		error_log('SSO registered system host lookup failed: ' . $exception->getMessage());
	}

	$hosts = array_values(array_unique($hosts));
	return $hosts;
}

function sso_is_registered_system_domain($url, $pdo) {
	if (!sso_is_safe_url($url)) {
		return false;
	}

	$host = sso_extract_host($url);
	if (!$host) {
		return false;
	}

	return in_array($host, sso_get_registered_system_hosts($pdo), true);
}

function sso_is_allowed_redirect_url($url, array $config, $pdo = null) {
	if (sso_is_allowed_domain($url, $config)) {
		return true;
	}

	if ($pdo instanceof PDO) {
		return sso_is_registered_system_domain($url, $pdo);
	}

	return false;
}

function sso_is_ams_system(array $system, array $config) {
	$systemName = strtolower(trim((string) ($system['system_name'] ?? '')));
	$domainUrl = trim((string) ($system['domain_url'] ?? ''));
	$amsBaseUrl = trim((string) ($config['ams_base_url'] ?? ''));

	if (
		$systemName !== '' && (
			(bool) preg_match('/(^|[^a-z0-9])ams([^a-z0-9]|$)/', $systemName)
			|| strpos($systemName, 'asset management') !== false
		)
	) {
		return true;
	}

	if (
		$domainUrl !== '' &&
		$amsBaseUrl !== ''
	) {
		$systemHost = sso_extract_host($domainUrl);
		$amsHost = sso_extract_host($amsBaseUrl);

		if (
			$systemHost !== null &&
			$amsHost !== null &&
			sso_host_matches($systemHost, $amsHost)
		) {
			return true;
		}
	}

	return false;
}

function sso_resolve_system_base_url(array $system, array $config) {
	$systemName = strtolower(trim((string) ($system['system_name'] ?? '')));
	$domainUrl = trim((string) ($system['domain_url'] ?? ''));

	if (sso_is_ams_system($system, $config) && !empty($config['ams_base_url'])) {
		return $config['ams_base_url'];
	}

	if ($systemName !== '' && strpos($systemName, 'helpdesk') !== false && !empty($config['helpdesk_base_url'])) {
		return $config['helpdesk_base_url'];
	}

	return $domainUrl;
}

function sso_build_login_url($baseUrl, $token, $loginPath = '/sso-login') {
	$baseUrl = trim((string) $baseUrl);
	$token = (string) $token;

	if ($baseUrl === '' || $token === '') {
		return null;
	}

	$parts = parse_url($baseUrl);
	if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
		return null;
	}

	$path = rtrim((string) ($parts['path'] ?? ''), '/');
	if ($path === '/sso-login' || substr($path, -strlen('/sso-login')) === '/sso-login') {
		$separator = empty($parts['query']) ? '?' : '&';
		return $baseUrl . $separator . 'token=' . urlencode($token);
	}

	return rtrim($baseUrl, '/') . $loginPath . '?token=' . urlencode($token);
}

function sso_apply_cors(array $config, $allowCredentials = false) {
	header('Vary: Origin');

	$origin = trim((string) ($_SERVER['HTTP_ORIGIN'] ?? ''));
	if ($origin === '') {
		return;
	}

	$allowedOrigins = $config['allowed_origins'] ?? [];
	if (in_array(rtrim($origin, '/'), $allowedOrigins, true)) {
		header('Access-Control-Allow-Origin: ' . $origin);
		if ($allowCredentials) {
			header('Access-Control-Allow-Credentials: true');
		}
	}
}

function sso_json_response($statusCode, array $payload) {
	http_response_code($statusCode);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($payload);
	exit;
}

function sso_client_ip() {
	return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

function sso_client_user_agent() {
	return substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'), 0, 255);
}

function sso_log_event($pdo, $portalId, $action, $details) {
	try {
		static $logColumns = null;
		static $logMeta = null;

		if ($logColumns === null || $logMeta === null) {
			$stmt = $pdo->query(
				"SELECT column_name, is_nullable FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'log_tbl'"
			);
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
			$logColumns = [];
			$logMeta = [];

			foreach ($rows as $row) {
				$columnName = (string) ($row['column_name'] ?? '');
				if ($columnName === '') {
					continue;
				}

				$logColumns[] = $columnName;
				$logMeta[$columnName] = [
					'nullable' => strtoupper((string) ($row['is_nullable'] ?? 'YES')) === 'YES',
				];
			}
		}

		$portalIdRequired = isset($logMeta['portal_id']) && $logMeta['portal_id']['nullable'] === false;
		if ($portalIdRequired && ($portalId === null || $portalId === '')) {
			error_log('SSO log skipped: portal_id is required for action ' . $action . ' with details ' . $details);
			return;
		}

		if (in_array('details', $logColumns, true)) {
			$stmt = $pdo->prepare(
				'INSERT INTO log_tbl (portal_id, action, details, ip_address, device_info) VALUES (:portal_id, :action, :details, :ip_address, :device_info)'
			);
			$stmt->execute([
				':portal_id' => $portalId,
				':action' => $action,
				':details' => $details,
				':ip_address' => sso_client_ip(),
				':device_info' => sso_client_user_agent(),
			]);
			return;
		}

		if (in_array('category', $logColumns, true) && in_array('accountable', $logColumns, true)) {
			$stmt = $pdo->prepare(
				'INSERT INTO log_tbl (portal_id, assignment_id, category, action, accountable, date_created) VALUES (:portal_id, :assignment_id, :category, :action, :accountable, NOW())'
			);
			$stmt->execute([
				':portal_id' => $portalId,
				':assignment_id' => null,
				':category' => 'sso',
				':action' => $action,
				':accountable' => substr($details, 0, 255),
			]);
		}
	} catch (Throwable $exception) {
		error_log('SSO log failure: ' . $exception->getMessage());
	}
}

function sso_get_tokens_tbl_columns($pdo) {
	static $columns = null;

	if ($columns !== null) {
		return $columns;
	}

	$stmt = $pdo->query(
		"SELECT column_name FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'tokens_tbl'"
	);

	$columns = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
	return $columns;
}

function sso_has_tokens_column($pdo, $columnName) {
	return in_array($columnName, sso_get_tokens_tbl_columns($pdo), true);
}

function sso_normalize_role($role) {
	$role = strtolower(trim((string) $role));
	$role = preg_replace('/[\s\-]+/', '_', $role);

	switch ($role) {
		case 'administrator':
		case 'superadmin':
		case 'super_admin':
			return 'admin';

		case 'sub_admin':
		case 'subadministrator':
		case 'sub_administrator':
			return 'subadmin';

		case 'ict':
		case 'ictd':
		case 'ict_division':
		case 'information_technology':
		case 'information_technology_division':
		case 'information_and_communication_technology':
		case 'information_and_communications_technology':
		case 'information_and_communication_technology_division':
		case 'information_and_communications_technology_division':
			return 'ict';

		case 'property_custodian':
		case 'property_officer':
		case 'property_management':
			return 'property';

		case 'employee':
		case 'enduser':
		case 'end_user':
			return 'user';
	}

	return $role;
}

function sso_allowed_role($role, array $config) {
	if (!is_string($role)) {
		return false;
	}

	return in_array(sso_normalize_role($role), $config['allowed_roles'], true);
}

function sso_mark_token_used($pdo, $token) {
	if (sso_has_tokens_column($pdo, 'used')) {
		$setClauses = ['used = TRUE'];
		if (sso_has_tokens_column($pdo, 'used_at')) {
			$setClauses[] = 'used_at = NOW()';
		}

		$sql = 'UPDATE tokens_tbl SET ' . implode(', ', $setClauses) . ' WHERE token = :token';
		$stmt = $pdo->prepare($sql);
		$stmt->execute([':token' => $token]);
		return;
	}

	$stmt = $pdo->prepare('DELETE FROM tokens_tbl WHERE token = :token');
	$stmt->execute([':token' => $token]);
}
