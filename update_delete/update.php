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

if (isset($_POST['spending_id'], $_POST['amount'], $_POST['date'], $_POST['category'])) {
    $spending_id = $_POST['spending_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $category = $_POST['category'];
    //echo "Received data: Amount - $amount, Date - $date, Category - $category, Spending ID - $spending_id<br>";
    // Validate amount
    if (!filter_var($amount, FILTER_VALIDATE_INT) || $amount < 0) {
        die("Amount must be a positive integer.");
    }

    // Validate date
    $dateObject = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateObject || $dateObject->format('Y-m-d') !== $date) {
        die("Date must be in YYYY-MM-DD format.");
    }

    // Validate category
    if (strlen($category) > 255) {
        die("Category must be 255 characters or less.");
    }


    // Update the database
    $stmt = $db->prepare("UPDATE Spending SET amount = :amount, date = :date, category = :category WHERE spending_id = :spending_id");
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':spending_id', $spending_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record updated successfully. Going back to the table in 3 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 3; URL=../spending_income.php');
    } else {
        $_SESSION['message'] = "Update failed. Going back to the table in 5 seconds.";
        echo $_SESSION['message'].  $stmt->error ;
        header('Refresh: 5; URL=../spending_income.php');
    }


    exit;
}
else{
    echo "POST data not set<br>";
}
?>

