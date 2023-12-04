<?php
session_start();
require('../connect-db.php');


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit;
}
if (isset($_POST['budget_id'])) {
    $budget_id = $_POST['budget_id'];

    // Prepare a delete statement to avoid SQL injection
    $stmt = $db->prepare("DELETE FROM Budget WHERE budget_id = ?");
    $stmt->execute([$budget_id]);

    if ($stmt->rowCount() > 0) {
        // Record was successfully deleted
        $_SESSION['message'] = "Record deleted successfully. Going back to the table in 3 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 3; URL=../budget_transac.php');
    } else {
        // Record wasn't deleted (e.g., it didn't exist)
        $_SESSION['message'] = "No record found with that ID. Going back to the table in 5 seconds.";
        echo $_SESSION['message'];
        header('Refresh: 5; URL=../budget_transac.php');
    }


    exit;
}
?>
