<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'constants.php';
require_once 'process/dbh.php';

if (session_status() == PHP_SESSION_NONE) {
    session_id("ssd-assignment");
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
    session_write_close();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit();
} else {
    $auth_code = $_GET['code'];
    $token = $client->fetchAccessTokenWithAuthCode($auth_code);
    if (is_array($token) && count($token) > 0) {
        $_SESSION['access_token'] = $token;
        $client->setAccessToken($token);
        // get profile info
        $oauth2 = new Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        // check userInfo is not null
        if ($userInfo == null || $userInfo->email == null) {
            // destroy session
            $_SESSION = [];
            session_destroy();
            $error = "no_email";
            session_write_close();
            header('Location: ' . filter_var($EMPLOYEE_LOGIN_URL . '?error=' . $error, FILTER_SANITIZE_URL));
            exit();
        } else {
            $_SESSION['email'] = $userInfo->email;

            // check if user is an employee
            $checkEmployee = "SELECT id FROM employee WHERE email = '" . $userInfo->email . "'";
            $result = mysqli_query($conn, $checkEmployee);
            $row = mysqli_fetch_array($result);

            if ($row[0] == 0) {
                // destroy session
                $_SESSION = [];
                session_destroy();
                $error = "not_employee";
                session_write_close();
                header('Location: ' . filter_var($EMPLOYEE_LOGIN_URL . '?error=' . $error, FILTER_SANITIZE_URL));
                exit();
            } else {
                // set session variables
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $userInfo->name;
                $_SESSION['picture'] = $userInfo->picture;
                $_SESSION['googleId'] = $userInfo->id;
                $_SESSION['firstName'] = $userInfo->givenName;


                if (isset($_SESSION['id'])) {
                    session_write_close();

                    header('Location: ' . filter_var($EMPLOYEE_WELCOME_URL, FILTER_SANITIZE_URL));
                    exit();
                } else {
                    session_write_close();
                    header('Location: ' . filter_var($EMPLOYEE_LOGIN_URL, FILTER_SANITIZE_URL));
                    exit();
                }
            }
        }
    } else {
        // destroy session
        $_SESSION = [];
        session_destroy();
        $error = "no_token";
        session_write_close();
        header('Location: ' . filter_var($EMPLOYEE_LOGIN_URL . '?error=' . $error, FILTER_SANITIZE_URL));
        exit();
    }
}
