<?php

require_once ('dbh.php');

$email = $_POST['mailuid'];
$password = $_POST['pwd'];

$sql = "SELECT * from `alogin` WHERE email = '$email' AND password = '$password'";

//echo "$sql";

$result = mysqli_query($conn, $sql);

$csrf_token = checkInput($_POST['csrf_token']);

// check if csrf token is valid
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) { 
    echo ('<meta http-equiv="Content-Security-Policy" content="default-src_\'self\'">'); 
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Request forgery detected') 
    window.location.href='javascript:history.go(-1)';
</SCRIPT>"); 
exit;
}
?>