<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="
		default-src 'self' https://fonts.googleapis.com; 
		font-src 'self' https://fonts.gstatic.com;">
    <title>Log In | XYZ Corporation</title>
    <link rel="stylesheet" type="text/css" href="stylelogin.css">
</head>

<body>
    <header>
        <nav>
            <h1>XYZ Corp.</h1>
            <ul id="navli">
                <li><a class="homeblack" href="index.html">HOME</a></li>
                <li><a class="homeblack" href="elogin.php">Employee Login</a></li>
                <li><a class="homered" href="alogin.php">Admin Login</a></li>

            </ul>
        </nav>
    </header>
    <div class="divider"></div>

    <div class="loginbox">
        <img src="assets/admin.png" class="avatar">
        <h1>Login Here</h1>

        <?php
        session_start();
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        ?>

        <form action="process/aprocess.php" method="POST">
            <p>Email</p>
            <input type="text" name="mailuid" placeholder="Enter Email Address" required="required">
            <p>Password</p>
            <input type="password" name="pwd" placeholder="Enter Password" required="required">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            <input type="submit" name="login-submit" value="Login">
        </form>

    </div>
</body>

</html>