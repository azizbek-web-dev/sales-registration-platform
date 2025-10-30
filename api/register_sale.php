<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_method('POST');
$body = get_json_input();
$sellerId = (int)($body['seller_id'] ?? 0);
$customerId = isset($body['customer_id']) ? (int)$body['customer_id'] : null;
$items = $body['items'] ?? [];
$paidAmount = (float)($body['paid_amount'] ?? 0);

if ($sellerId <= 0 || !is_array($items) || count($items) === 0) {
	json_response(['error' => 'Invalid payload'], 400);
}

try {
	$pdo->beginTransaction();

	$total = 0.0;
	foreach ($items as $it) {
		$productId = (int)($it['product_id'] ?? 0);
		$qty = (int)($it['quantity'] ?? 0);
		if ($productId <= 0 || $qty <= 0) {
			throw new RuntimeException('Invalid item');
		}
		$prod = $pdo->prepare('SELECT id, price, stock FROM products WHERE id = ? FOR UPDATE');
		$prod->execute([$productId]);
		$row = $prod->fetch();
		if (!$row || $row['stock'] < $qty) {
			throw new RuntimeException('Insufficient stock');
		}
		$total += (float)$row['price'] * $qty;

		$upd = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
		$upd->execute([$qty, $productId]);
	}

	$saleStmt = $pdo->prepare('INSERT INTO sales (seller_id, customer_id, total_amount, paid_amount) VALUES (?,?,?,?)');
	$saleStmt->execute([$sellerId, $customerId, $total, $paidAmount]);
	$saleId = (int)$pdo->lastInsertId();

	$itemStmt = $pdo->prepare('INSERT INTO sale_items (sale_id, product_id, quantity, unit_price) VALUES (?,?,?,?)');
	foreach ($items as $it) {
		$productId = (int)$it['product_id'];
		$qty = (int)$it['quantity'];
		$priceRow = $pdo->prepare('SELECT price FROM products WHERE id = ?');
		$priceRow->execute([$productId]);
		$price = (float)$priceRow->fetchColumn();
		$itemStmt->execute([$saleId, $productId, $qty, $price]);
	}

	$pdo->commit();
	json_response(['sale_id' => $saleId, 'total' => $total, 'paid' => $paidAmount]);
} catch (Throwable $e) {
	if ($pdo->inTransaction()) {
		$pdo->rollBack();
	}
	json_response(['error' => 'Sale registration failed'], 400);
}
