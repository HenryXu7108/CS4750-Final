<?php
session_start();
require('../connect-db.php');


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit;
}
if (isset($_POST['spending_id'])) {
    $spending_id = $_POST['spending_id'];

    // Prepare a delete statement to avoid SQL injection
    $stmt = $db->prepare("DELETE FROM Spending WHERE spending_id = ?");
    $stmt->execute([$spending_id]);

    if ($stmt->rowCount() > 0) {
        // Record was successfully deleted
        $_SESSION['message'] = "Record deleted successfully. Going back to the table in 3 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 3; URL=../spending_income.php');
    } else {
        // Record wasn't deleted (e.g., it didn't exist)
        $_SESSION['message'] = "No record found with that ID. Going back to the table in 5 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 5; URL=../spending_income.php');
    }


    exit;
}
?>
