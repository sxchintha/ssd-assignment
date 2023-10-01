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
// $client->addScope(Google\Service\Drive::DRIVE_METADATA_READONLY);
// add scope to see primary google account email address
$client->addScope(Google\Service\Oauth2::USERINFO_EMAIL);
$client->addScope(Google\Service\Oauth2::USERINFO_PROFILE);


if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    echo "Access token is set";
    $client->setAccessToken($_SESSION['access_token']);
    // get profile info
    $oauth2 = new Google\Service\Oauth2($client);
    $userInfo = $oauth2->userinfo->get();
    // echo "<pre>";
    print_r($userInfo);
    // echo "</pre>";
    // exit;
    // echo json_encode($files);
} else {
    echo "Access token is not set";
    $redirect_uri = 'http://localhost/ssd-assignment/oauth2callback.php';
    //   $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/ssd-assignment/oauth2callback.php';
    //   header('Location: ' . $redirect_uri);
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
