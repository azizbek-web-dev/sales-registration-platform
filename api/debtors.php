<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_method('GET');

$sql = "SELECT s.id, s.created_at, s.total_amount, s.paid_amount,
		(s.total_amount - s.paid_amount) AS balance,
		cu.id AS customer_id, cu.name AS customer_name
	FROM sales s
	LEFT JOIN users cu ON cu.id = s.customer_id
	WHERE (s.total_amount - s.paid_amount) > 0
	ORDER BY s.created_at DESC";

$stmt = $pdo->query($sql);
json_response(['debtors' => $stmt->fetchAll()]);
