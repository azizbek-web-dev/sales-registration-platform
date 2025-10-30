<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="login-wrap">
	<div class="login-bg"></div>
	<div class="login-card">
		<div class="logo"><span class="logo-dot"></span><strong>Sales Platform</strong></div>
		<h2 class="login-title">Sign in</h2>
		<p class="login-sub">Access your dashboard</p>
		<form id="login-form" onsubmit="return false;">
			<label for="email">Email</label>
			<input type="email" id="email" name="email" required>
			<label for="password">Password</label>
			<input type="password" id="password" name="password" required>
			<div style="margin-top:14px">
				<button id="login-btn" type="submit" style="width:100%">Login</button>
			</div>
		</form>
		<p id="msg" class="alert error" style="display:none;margin-top:12px"></p>
	</div>
</div>
<script>
const form = document.getElementById('login-form');
const btn = document.getElementById('login-btn');
const msg = document.getElementById('msg');
form.addEventListener('submit', async () => {
	btn.disabled = true;
	msg.style.display = 'none';
	try {
		const payload = { email: form.email.value.trim(), password: form.password.value };
		const res = await fetch('/api/login.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
		const data = await res.json();
		if (!res.ok) throw new Error(data.error || 'Login failed');
		switch (data.user.role) {
			case 'admin': window.location.href = '/public/dashboard_admin.php'; break;
			case 'seller': window.location.href = '/public/dashboard_seller.php'; break;
			default: window.location.href = '/public/dashboard_customer.php';
		}
	} catch (e) {
		msg.textContent = e.message || 'Login failed';
		msg.style.display = 'block';
	} finally {
		btn.disabled = false;
	}
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
