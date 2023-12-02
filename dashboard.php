<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit;
}

echo "Welcome " . $_SESSION['name'] . "!";
echo "<p><a href='logout.php'>Logout</a></p>";

?>

<a href="insert.php">Click to insert a record</a>

