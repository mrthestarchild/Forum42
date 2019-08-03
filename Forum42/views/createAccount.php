<?php

// creates a instance of the CreateAccountController class
// to send request to create account on submit
require_once( WEB_CTLS_CREATE_ACCOUNT );
$response = "";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createAccount'])){
	$create = new CreateAccountController();
	$response = $create->createAccount();
}
?>
<div class="wrapper">
	<div class="signup-container">
		<div class="form-container">
			<form method="post">
				<label>Username:</label>
				<input type="hidden" name="createAccount" value="yes">
				<input type="text" id="username" name="username" onkeyup="removeSpaces('username')" required>
				<label>Password:</label>
				<input type="password" id="password" name="password" required>
				<label>E-mail:</label>
				<input type="email" id="email" name="email">
				<?php print("<p class = 'error'> $response </p>"); ?>
				<input type="submit"  value="Sign Up">
			</form>
		</div>
	</div>
</div>