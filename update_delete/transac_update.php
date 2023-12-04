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

if (isset($_POST['transaction_id'], $_POST['amount'], $_POST['transaction_date'], $_POST['description'])) {
    $transaction_id = $_POST['transaction_id'];
    $transaction_date = $_POST['transaction_date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    // Validate bill amount
    if (!is_numeric($amount)) {
        die("Bill amount must be a number.");
    }

    // Using regular expression to validate that bill_amount has up to two decimal places
    if (!preg_match("/^\d+(\.\d{1,2})?$/", $amount)) {
        die("Bill amount must be a number with up to two decimal places.");
    }

    if (strlen($description) > 255) {
        die("Category must be 255 characters or less.");
    }

    // Update the database
    $stmt = $db->prepare("UPDATE Transaction SET amount = :amount,  description = :description, transaction_date = :transaction_date WHERE transaction_id = :transaction_id");
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':transaction_date', $transaction_date);

    $stmt->bindParam(':transaction_id', $transaction_id);

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

