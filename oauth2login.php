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
$client->addScope(Google\Service\Oauth2::USERINFO_EMAIL);
$client->addScope(Google\Service\Oauth2::USERINFO_PROFILE);


if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    try {
        $client->setAccessToken($_SESSION['access_token']);
        // get profile info
        $oauth2 = new Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();
    } catch (Exception $e) {
        $_SESSION = [];
        session_destroy();
        session_write_close();
        header('Location: ' . filter_var($AUTH_CALLBACK_URL, FILTER_SANITIZE_URL));
    }

    // check userInfo is not null
    if ($userInfo == null || $userInfo->email == null) {
        // destroy session
        $_SESSION = [];
        session_destroy();
        session_write_close();
        header('Location: ' . filter_var($AUTH_CALLBACK_URL, FILTER_SANITIZE_URL));
    } else {
        $userEmail = $userInfo->email;

        // check if user is an employee
        $checkEmployee = "SELECT id FROM employee WHERE email = '" . $userEmail . "'";
        $result = mysqli_query($conn, $checkEmployee);
        $row = mysqli_fetch_array($result);

        if ($row[0] == 0) {
            // destroy session
            $_SESSION = [];
            session_destroy();
            $error = "not_employee";
            session_write_close();
            header('Location: ' . filter_var($EMPLOYEE_LOGIN_URL . '?error=' . $error, FILTER_SANITIZE_URL));
        } else {
            // set session variables
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $userInfo->name;
            $_SESSION['picture'] = $userInfo->picture;
            $_SESSION['googleId'] = $userInfo->id;
            $_SESSION['firstName'] = $userInfo->givenName;
            $_SESSION['email'] = $userEmail;

            session_write_close();
            header('Location: ' . filter_var($EMPLOYEE_WELCOME_URL, FILTER_SANITIZE_URL));
        }
    }
} else {
    session_write_close();
    header('Location: ' . filter_var($AUTH_CALLBACK_URL, FILTER_SANITIZE_URL));
}
