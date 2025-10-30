<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login • Sales Platform</title>
	<link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>
	<div class="login-wrap">
		<div class="login-bg"></div>
		<div class="login-card">
			<img class="logo-img" src="/public/assets/images/sale-logo.png" alt="Sales Platform">
			<h2 class="login-title" style="text-align:center">Sign in</h2>
			<p class="login-sub" style="text-align:center">Access your dashboard</p>
			<form id="login-form" onsubmit="return false;">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" required>
				<label for="password">Password</label>
				<div class="field-wrap">
					<input type="password" id="password" name="password" required>
					<button type="button" id="toggle-eye" class="toggle-eye" aria-label="Show password">
						<img id="eye-icon" src="/public/assets/icons/eye.svg" alt="toggle" width="20" height="20"/>
					</button>
				</div>
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
	const pwd = document.getElementById('password');
	const toggle = document.getElementById('toggle-eye');
	const eyeIcon = document.getElementById('eye-icon');
	toggle.addEventListener('click', () => {
		const isHidden = pwd.type === 'password';
		pwd.type = isHidden ? 'text' : 'password';
		eyeIcon.src = isHidden ? '/public/assets/icons/eye-off.svg' : '/public/assets/icons/eye.svg';
		toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
	});

	async function parseJsonSafe(res){
		const contentType = res.headers.get('content-type') || '';
		const bodyText = await res.text();
		if (contentType.includes('application/json')) {
			try { return JSON.parse(bodyText); } catch (_) { return null; }
		}
		try { return JSON.parse(bodyText); } catch (_) { return { error: bodyText || 'Unexpected empty response' }; }
	}

	form.addEventListener('submit', async () => {
		btn.disabled = true;
		msg.style.display = 'none';
		try {
			const payload = { email: form.email.value.trim(), password: form.password.value };
			const res = await fetch('../api/login.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
			const data = await parseJsonSafe(res);
			if (!res.ok) throw new Error((data && data.error) ? data.error : `HTTP ${res.status}`);
			if (!data || !data.user) throw new Error('Invalid server response');
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
</body>
</html>
