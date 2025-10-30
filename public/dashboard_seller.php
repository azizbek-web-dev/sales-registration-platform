<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="card">
	<h2>Seller Dashboard</h2>
	<p>Register a simple sale (single item):</p>
	<form id="sale-form" onsubmit="return false;">
		<label for="seller_id">Seller ID</label>
		<input id="seller_id" name="seller_id" type="number" value="2" required>
		<label for="product_id">Product ID</label>
		<input id="product_id" name="product_id" type="number" value="1" required>
		<label for="quantity">Quantity</label>
		<input id="quantity" name="quantity" type="number" value="1" required>
		<label for="paid_amount">Paid Amount</label>
		<input id="paid_amount" name="paid_amount" type="number" step="0.01" value="0">
		<button id="submit-btn">Register Sale</button>
	</form>
	<p id="result" class="alert info" style="display:none"></p>
</div>
<script>
const form = document.getElementById('sale-form');
const result = document.getElementById('result');
const btn = document.getElementById('submit-btn');
form.addEventListener('submit', async () => {
	btn.disabled = true;
	result.style.display = 'none';
	try {
		const payload = {
			seller_id: Number(form.seller_id.value),
			items: [{ product_id: Number(form.product_id.value), quantity: Number(form.quantity.value) }],
			paid_amount: Number(form.paid_amount.value || 0)
		};
		const res = await fetch('/api/register_sale.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
		const data = await res.json();
		result.textContent = res.ok ? `Sale #${data.sale_id} created. Total: ${data.total}` : (data.error || 'Failed');
		result.className = res.ok ? 'alert info' : 'alert error';
		result.style.display = 'block';
	} catch (e) {
		result.textContent = 'Request failed';
		result.className = 'alert error';
		result.style.display = 'block';
	} finally {
		btn.disabled = false;
	}
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
