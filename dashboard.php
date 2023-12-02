<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit;
}

echo "Welcome " . $_SESSION['name'] . "!";
// Log out link
echo "<p><a href='logout.php'>Logout</a></p>";

?>

<a href="insert.php">Click to insert a record</a>

