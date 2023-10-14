<?php
session_start();
require_once('dbh.php');
require_once('checkInput.php');

// check request method
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['mailuid']) || !isset($_POST['pwd']) || !isset($_POST['csrf_token'])) {
    header("Location: ../403.html?error=method");
    exit;
}

$csrf_token = checkInput($_POST['csrf_token']);

// check if csrf token is valid
// if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
//     echo ('<meta http-equiv="Content-Security-Policy" content="default-src \'self\'">');
//     echo ("<SCRIPT LANGUAGE='JavaScript'>
//     window.alert('Request forgery detected')
//     window.location.href='javascript:history.go(-1)';
//     </SCRIPT>");
//     exit;
// }

// check if csrf token is valid
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    header("Location: ../403.html?error=csrf");
    exit;
}

// $sql = "SELECT * from `alogin` WHERE email = '$email' AND password = '$password'";

// prepared statement for login
$sql = "SELECT * from `alogin` WHERE email = ? AND password = ?";

// create a prepared statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);

$email = checkInput($_POST['mailuid']);
$password = checkInput($_POST['pwd']);

// $result = mysqli_query($conn, $sql);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) == 1) {
    //echo ("logged in");
    header("Location: ../aloginwel.php");
} else {
    header("Location: ../alogin.php?error=Invalid Email or Password");
}
