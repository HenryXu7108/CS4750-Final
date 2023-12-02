<?php
session_start();
require ('connect-db.php');
require_once ('auth_functions.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        exit('Please fill both the username and password fields!');
    }

    $user = getUser($username);
    var_dump($user['password']);
    
    if ($user && verifyUserPassword($password, $user['password'])) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $username;
        $_SESSION['id'] = $user['id'];
        header('Location: dashboard.php');
    } else {
        echo 'Incorrect username and/or password!';
        header('Refresh: 5; URL=login.php'); // Redirect to the Login Page after 2 seconds
    }
}
?>
