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

if (isset($_POST['card_id'], $_POST['credit_limit'], $_POST['interest_rate'])) {
    $card_id = $_POST['card_id'];
    $credit_limit= $_POST['credit_limit'];
    $interest_rate = $_POST['interest_rate'];


    if (!filter_var($credit_limit, FILTER_VALIDATE_FLOAT)) {
        die("Credit limit must be a valid number.");
    }

    if (!preg_match("/^\d{1,10}$/", $interest_rate)) {
        die("Interest rate must be a number with up to 10 digits and no decimal places.");
    }


    // Update the database
    $stmt = $db->prepare("UPDATE Credit_Card SET interest_rate = :interest_rate,credit_limit = :credit_limit WHERE card_id = :card_id");
    $stmt->bindParam(':interest_rate', $interest_rate);
    $stmt->bindParam(':credit_limit', $credit_limit);
    $stmt->bindParam(':card_id', $card_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record updated successfully. Going back to the table in 3 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 3; URL=../card.php');
    } else {
        $_SESSION['message'] = "Update failed. Going back to the table in 5 seconds.";
        echo $_SESSION['message'].  $stmt->error ;
        header('Refresh: 5; URL=../card.php');
    }


    exit;
}
else{
    echo "POST data not set<br>";
}
?>

