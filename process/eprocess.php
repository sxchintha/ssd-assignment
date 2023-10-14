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
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    header("Location: ../403.html?error=csrf");
    exit;
}

$email = checkInput($_POST['mailuid']);
$password = checkInput($_POST['pwd']);

$sql = "SELECT * from `employee` WHERE email = '$email' AND password = '$password'";
$sqlid = "SELECT id from `employee` WHERE email = '$email' AND password = '$password'";

$result = mysqli_query($conn, $sql);
$id = mysqli_query($conn, $sqlid);

$empid = "";
if (mysqli_num_rows($result) == 1) {

	$employee = mysqli_fetch_array($id);
	$empid = ($employee['id']);

    $_SESSION['empid'] = $empid;

	//echo ("logged in");
	//echo ("$empid");

	header("Location: ../eloginwel.php");
} else {
	header("Location: ../elogin.php?error=Invalid Email or Password");
}
