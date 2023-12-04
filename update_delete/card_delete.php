<?php
session_start();
require('../connect-db.php');


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit;
}
if (isset($_POST['card_id'])) {
    $card_id = $_POST['card_id'];

    // Prepare a delete statement to avoid SQL injection
    $stmt = $db->prepare("DELETE FROM Card WHERE card_id = ?");
    $stmt->execute([$card_id]);

    if ($stmt->rowCount() > 0) {
        // Record was successfully deleted
        $_SESSION['message'] = "Record deleted successfully. Going back to the table in 3 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 3; URL=../card.php');
    } else {
        // Record wasn't deleted (e.g., it didn't exist)
        $_SESSION['message'] = "No record found with that ID. Going back to the table in 5 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 5; URL=../card.php');
    }


    exit;
}
?>
