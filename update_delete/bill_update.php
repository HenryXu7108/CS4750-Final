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
//bill_amount, due_date, description, bill_status,
if (isset($_POST['notification_id'], $_POST['bill_amount'], $_POST['description'], $_POST['due_date'], $_POST['bill_status'])) {
    $notification_id = $_POST['notification_id'];
    $bill_amount = $_POST['bill_amount'];
    $description = $_POST['description'];
    $bill_status = $_POST['bill_status'];
    $due_date = $_POST['due_date'];


    // Validate bill amount
    if (!is_numeric($bill_amount)) {
        die("Bill amount must be a number.");
    }

    // Using regular expression to validate that bill_amount has up to two decimal places
    if (!preg_match("/^\d+(\.\d{1,2})?$/", $bill_amount)) {
        die("Bill amount must be a number with up to two decimal places.");
    }
    if (strlen($description) > 255) {
        die("Category must be 255 characters or less.");
    }
    $dateObject = DateTime::createFromFormat('Y-m-d', $due_date);
    if (!$dateObject || $dateObject->format('Y-m-d') !== $due_date) {
        die("Date must be in YYYY-MM-DD format.");
    }

    // Update the database
    $stmt = $db->prepare("UPDATE BillNotification SET bill_amount = :bill_amount,  description = :description, bill_status = :bill_status, due_date = :due_date WHERE notification_id = :notification_id");
    $stmt->bindParam(':bill_amount', $bill_amount);
    $stmt->bindParam(':notification_id', $notification_id);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':bill_status', $bill_status);
    $stmt->bindParam(':due_date', $due_date);



    if ($stmt->execute()) {
        $_SESSION['message'] = "Record updated successfully. Going back to the table in 3 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 3; URL=../billnoti.php');
    } else {
        $_SESSION['message'] = "Update failed. Going back to the table in 5 seconds.";
        echo $_SESSION['message'].  $stmt->error ;
        header('Refresh: 5; URL=../billnoti.php');
    }


    exit;
}
else{
    echo "POST data not set<br>";
}
?>

