<?php
require ('connect-db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password) || empty($email)) {
        exit('Please fill all the required fields!');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO Person (username, email, password) VALUES (:username, :email, :password)");
    
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $hashedPassword);
    
    try {
        $stmt->execute();
        echo 'You have successfully created a new account';
        header('Refresh: 2; URL=login.php');
    } catch (PDOException $e) {
        exit("Error during registration: " . $e->getMessage());
    }
}

?>
