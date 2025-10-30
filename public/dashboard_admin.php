<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="card">
	<h2>Admin Dashboard</h2>
	<ul>
		<li><a href="/api/get_stock.php" target="_blank">View Stock (JSON)</a></li>
		<li><a href="/api/debtors.php" target="_blank">View Debtors (JSON)</a></li>
		<li><a href="/api/alert.php?threshold=5" target="_blank">Low Stock Alerts (JSON)</a></li>
	</ul>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
