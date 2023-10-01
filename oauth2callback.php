<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'constants.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$client = new Google\Client();
$client->setAuthConfig('client_secret.json');
$client->setAccessType('offline');
$client->setRedirectUri($AUTH_CALLBACK_URL);
$client->addScope(Google\Service\Oauth2::USERINFO_EMAIL);
$client->addScope(Google\Service\Oauth2::USERINFO_PROFILE);

if (!isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    $auth_code = $_GET['code'];
    $token = $client->fetchAccessTokenWithAuthCode($auth_code);
    if (is_array($token) && count($token) > 0) {
        $_SESSION['access_token'] = $token;
        header('Location: ' . filter_var($AUTH_LOGIN_URL, FILTER_SANITIZE_URL));
    } else {
        // destroy session
        $_SESSION = [];
        session_destroy();
        $error = "no_token";
        header('Location: ' . filter_var($EMPLOYEE_LOGIN_URL . '?error=' . $error, FILTER_SANITIZE_URL));
    }
}
