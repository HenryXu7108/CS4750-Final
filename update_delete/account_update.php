<?php
session_start();
require('../connect-db.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Security checks
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit;
}

if (isset($_POST['account_id'], $_POST['account_number'],  $_POST['account_type'],  $_POST['balance'],$_POST['account_status'])) {
    $account_id = $_POST['account_id'];
    $account_number = $_POST['account_number'];
    $account_type = $_POST['account_type'];
    $balance = $_POST['balance'];
    $account_status = $_POST['account_status'];
    // Validate account number (length constraint)
    if (strlen($account_number) > 255) {
        die("Account number must be 255 characters or less.");
    }

// Validate account type (enum constraint)
    $valid_account_types = ['CHECKING', 'SAVINGS', 'CREDIT'];
    if (!in_array($account_type, $valid_account_types)) {
        die("Invalid account type.");
    }

// Validate balance (decimal constraint)
    if (!is_numeric($balance) || $balance < 0) {
        die("Invalid balance. Balance must be a positive number.");
    }

// Validate account status (enum constraint)
    $valid_account_statuses = ['ACTIVE', 'INACTIVE', 'CLOSED'];
    if (!in_array($account_status, $valid_account_statuses)) {
        die("Invalid account status.");
    }

    // Update the database
    $stmt = $db->prepare("UPDATE Account SET account_number = :account_number,  balance = :balance ,  account_type = :account_type ,  account_status = :account_status WHERE account_id = :account_id");
    $stmt->bindParam(':account_number', $account_number);
    $stmt->bindParam(':balance', $balance);
    $stmt->bindParam(':account_type', $account_type);
    $stmt->bindParam(':account_status', $account_status);

    $stmt->bindParam(':account_id', $account_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record updated successfully. Going back to the table in 3 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 3; URL=../budget_transac.php');
    } else {
        $_SESSION['message'] = "Update failed. Going back to the table in 5 seconds.";
        echo $_SESSION['message'].  $stmt->error ;
        header('Refresh: 5; URL=../budget_transac.php');
    }


    exit;
}
else{
    echo "POST data not set<br>";
}
?>

