<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_method('GET');
$threshold = isset($_GET['threshold']) ? max(0, (int)$_GET['threshold']) : 10;

$stmt = $pdo->prepare('SELECT id, name, sku, stock FROM products WHERE stock <= ? ORDER BY stock ASC');
$stmt->execute([$threshold]);
json_response(['alerts' => $stmt->fetchAll(), 'threshold' => $threshold]);
