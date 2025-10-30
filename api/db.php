<?php
if (!getenv('APP_ENV')) { putenv('APP_ENV=dev'); }
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'sales_platform';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: 'secret';
$DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";
$options = [
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES => false,
];

try {
	$pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (Throwable $e) {
	http_response_code(500);
	header('Content-Type: application/json');
	$debug = getenv('APP_ENV') === 'dev' ? ['detail' => $e->getMessage()] : [];
	echo json_encode(array_merge(['error' => 'Database connection failed'], $debug));
	exit;
}
