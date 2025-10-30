<?php
function json_response($data, int $code = 200): void {
	http_response_code($code);
	header('Content-Type: application/json');
	echo json_encode($data);
	exit;
}

function require_method(string $method): void {
	if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($method)) {
		json_response(['error' => 'Method Not Allowed'], 405);
	}
}

function get_json_input(): array {
	$raw = file_get_contents('php://input');
	$decoded = json_decode($raw, true);
	return is_array($decoded) ? $decoded : [];
}

function hash_password(string $password): string {
	return password_hash($password, PASSWORD_BCRYPT);
}

function verify_password(string $password, string $hash): bool {
	return password_verify($password, $hash);
}
