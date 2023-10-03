<?php
session_id("ssd-assignment");
session_start();
require_once '../constants.php';

$_SESSION = [];
session_destroy();
header('Location: ' . filter_var($BASE_URL, FILTER_SANITIZE_URL));
