<?php
require_once __DIR__ . '/vendor/autoload.php';

// check if session is already started
echo "Session status: " . session_status();
echo "<br>";
if (session_status() == PHP_SESSION_NONE) {
    echo "Starting session";
    echo "<br>";
    session_start();
}

$client = new Google\Client();
$client->setAuthConfig('client_secret.json');
$client->setAccessType('offline');
$client->setRedirectUri('http://localhost/ssd-assignment/oauth2callback.php');
$client->addScope(Google\Service\Oauth2::USERINFO_EMAIL);
$client->addScope(Google\Service\Oauth2::USERINFO_PROFILE);

if (!isset($_GET['code'])) {
    echo "Code is not set";
    echo "<br>";
    $auth_url = $client->createAuthUrl();
    echo "Auth url: " . $auth_url;
    echo "<br>";
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    echo "Code is set";
    echo "<br>";
    $auth_code = $_GET['code'];
    echo "Auth code: " . $auth_code;
    echo "<br>";

    if ($client->getAccessToken()) {
        echo "Access token is set. Revoking token";
        echo "<br>";
    } else {
        echo "Access token is not set";
        echo "<br>";
    }

    $token = $client->fetchAccessTokenWithAuthCode($auth_code);
    if (is_array($token) && count($token) > 0) {
        $_SESSION['access_token'] = $token;
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        // header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    } else {
        // Handle the error here
    }
}

// header('Location: ' . filter_var('http://localhost/ssd-assignment'));
