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

if (isset($_POST['investment_id'], $_POST['amount_invested'],$_POST['investment_type'], $_POST['investment_date'], $_POST['maturity_date'], $_POST['current_value'], $_POST['rate_of_return'], $_POST['description'])) {
    //investment_type, amount_invested, investment_date, maturity_date, current_value, rate_of_return, description

    $investment_id = $_POST['investment_id'];
    $amount_invested = $_POST['amount_invested'];
    $investment_date = $_POST['investment_date'];
    $maturity_date = $_POST['maturity_date'];
    $current_value = $_POST['current_value'];
    $rate_of_return = $_POST['rate_of_return'];
    $description = $_POST['description'];
    $investment_type = $_POST['investment_type'];

    $dateObject = DateTime::createFromFormat('Y-m-d', $investment_date);
    if (!$dateObject || $dateObject->format('Y-m-d') !== $investment_date) {
        die("Date must be in YYYY-MM-DD format.");
    }
    $dateObject = DateTime::createFromFormat('Y-m-d', $maturity_date);
    if (!$dateObject || $dateObject->format('Y-m-d') !== $maturity_date) {
        die("Date must be in YYYY-MM-DD format.");
    }

    if (!preg_match("/^\d{1,13}(\.\d{1,2})?$/", $amount_invested) || !preg_match("/^\d{1,13}(\.\d{1,2})?$/", $current_value)) {
        die("Amount invested and current value must be numbers with up to 13 digits and 2 decimal places.");
    }

    if (!preg_match("/^\d{1,13}(\.\d{1,2})?$/", $current_value) || !preg_match("/^\d{1,13}(\.\d{1,2})?$/", $current_value)) {
        die("Amount invested and current value must be numbers with up to 13 digits and 2 decimal places.");
    }

    if (!preg_match("/^\d{1,3}(\.\d{1,2})?$/", $rate_of_return)) {
        die("Rate of return must be a number with up to 3 digits and 2 decimal places.");
    }


    if (strlen($description) > 255) {
        die("Category must be 255 characters or less.");
    }


    // Update the database
    $stmt = $db->prepare("UPDATE Investment SET investment_type=:investment_type, amount_invested = :amount_invested, maturity_date = :maturity_date, investment_date = :investment_date, current_value = :current_value, rate_of_return = :rate_of_return, description = :description WHERE investment_id = :investment_id");

    $stmt->bindParam(':amount_invested', $amount_invested);
    $stmt->bindParam(':investment_id', $investment_id);
    $stmt->bindParam(':maturity_date', $maturity_date);
    $stmt->bindParam(':investment_date', $investment_date);
    $stmt->bindParam(':current_value', $current_value);
    $stmt->bindParam(':rate_of_return', $rate_of_return);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':investment_type', $investment_type);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record updated successfully. Going back to the table in 3 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 3; URL=../investment.php');
    } else {
        $_SESSION['message'] = "Update failed. Going back to the table in 5 seconds.";
        // Use errorInfo() method to get the error details
        echo $_SESSION['message'] . implode(", ", $stmt->errorInfo());
        header('Refresh: 5; URL=../investment.php');
    }



    exit;
}
else{
    echo "POST data not set<br>";
}
?>

