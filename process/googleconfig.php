<?php

define('GOOGLE_CLIENT_ID', '57444869575-f6itentna8hhugkgro1uf8ea2ms2p68d.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/ssd-assignment/loginsuccess.php');

// Include the Google OAuth 2.0 library.
require_once 'vendor/autoload.php';

// Create an authorization object using your client ID and client secret.
$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);

// Redirect the user to the Google OAuth 2.0 authorization endpoint.
$authUrl = $client->createAuthUrl();
echo $authUrl;
// header('Location: ' . $authUrl);
exit;


?>