<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_method('GET');
$sku = isset($_GET['sku']) ? trim($_GET['sku']) : '';

if ($sku !== '') {
	$stmt = $pdo->prepare('SELECT id, name, sku, price, stock FROM products WHERE sku = ? LIMIT 1');
	$stmt->execute([$sku]);
	$product = $stmt->fetch();
	if (!$product) {
		json_response(['error' => 'Not found'], 404);
	}
	json_response(['product' => $product]);
}

$stmt = $pdo->query('SELECT id, name, sku, price, stock FROM products ORDER BY name');
json_response(['products' => $stmt->fetchAll()]);
