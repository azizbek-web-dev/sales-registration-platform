<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/functions.php';

try {
	require_method('POST');
	$body = get_json_input();
	$email = trim($body['email'] ?? '');
	$password = (string)($body['password'] ?? '');
	$role = $body['role'] ?? null; // optional filter

	if ($email === '' || $password === '') {
		json_response(['error' => 'Email and password required'], 400);
	}

	$stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
	$stmt->execute([$email]);
	$user = $stmt->fetch();

	if (!$user || !password_verify($password, $user['password_hash'])) {
		json_response(['error' => 'Invalid credentials'], 401);
	}

	if ($role && $user['role'] !== $role) {
		json_response(['error' => 'Role not permitted'], 403);
	}

	unset($user['password_hash']);
	json_response(['user' => $user]);
} catch (Throwable $e) {
	$debug = getenv('APP_ENV') === 'dev' ? ['detail' => $e->getMessage()] : [];
	http_response_code(500);
	header('Content-Type: application/json');
	echo json_encode(array_merge(['error' => 'Server error'], $debug));
	exit;
}
