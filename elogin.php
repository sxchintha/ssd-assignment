<!DOCTYPE html>
<html lang="en">

<?php
session_id("ssd-assignment");
session_start();
?>

<head>
	<title>Log In | XYZ Corporation</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="stylelogin.css">
</head>

<body>
	<header>
		<nav>
			<h1>XYZ Corp.</h1>
			<ul id="navli">
				<li><a class="homeblack" href="index.html">HOME</a></li>
				<li><a class="homered" href="elogin.php">Employee Login</a></li>
				<li><a class="homeblack" href="alogin.html">Admin Login</a></li>

			</ul>
		</nav>
	</header>
	<div class="divider"></div>

	<div class="loginbox">
		<img src="assets/avatar.png" class="avatar" alt="avatar" />
		<h1>Login Here</h1>
		<form action="process/eprocess.php" method="POST">
			<p>Email</p>
			<input type="text" name="mailuid" placeholder="Enter Email Address" required="required">
			<p>Password</p>
			<input type="password" name="pwd" placeholder="Enter Password" required="required">
			<input type="submit" name="login-submit" value="Login">

			<a href="oauth2login.php">
				<div id="btn-google-login"></div>
			</a>
			<br>
		</form>

	</div>


</body>

<script>
	<?php
	if (isset($_GET['error'])) {
		$error = $_GET['error'];
		if ($error == 'no_token') {
			echo "alert('No token! Please login again!')";
		} else if ($error == 'not_employee') {
			echo "alert('You are not an employee!')";
		} else if ($error == 'not_admin') {
			echo "alert('You are not an admin!')";
		}
	}
	?>
</script>

</html>