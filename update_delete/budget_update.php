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

if (isset($_POST['budget_id'], $_POST['amount'],  $_POST['purpose'])) {
    $income_id = $_POST['budget_id'];
    $amount = $_POST['amount'];
    $purpose = $_POST['purpose'];

    // Validate amount
    if (!filter_var($amount, FILTER_VALIDATE_INT) || $amount < 0) {
        die("Amount must be a positive integer.");
    }

    // Validate date


    if (strlen($purpose) > 255) {
        die("Category must be 255 characters or less.");
    }

    // Update the database
    $stmt = $db->prepare("UPDATE Budget SET amount = :amount,  purpose = :purpose WHERE budget_id = :budget_id");
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':purpose', $purpose);

    $stmt->bindParam(':budget_id', $budget_id);

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

