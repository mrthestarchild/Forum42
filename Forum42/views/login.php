<?php

// creates a instance of the LoginController class
// to send request to login user on submit
require_once(WEB_CTLS_LOGIN_USER);
$response = "";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginForm'])){
	$login = new LoginController();
	$response = $login->login();
}
?>
<div class="signup-container">
	<div class="form-container">
		<form method="post">
			<input type="hidden" name="loginForm" value="yes" >
			<label>Username:</label>
			<input type="text" id="username" name="username" required>
			<label>Password:</label>
			<input type="password" id="password" name="password" required>
			<?php print("<p class = 'error'> $response </p>"); ?>
			<input type="submit" value="Login" name="login">
		</form>
	</div>
</div>