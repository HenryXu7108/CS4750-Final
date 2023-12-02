<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Database connection
    $db = new mysqli('host', 'user', 'password', 'database');

    // Prevent SQL Injection
    $stmt = $db->prepare("DELETE FROM Spending WHERE spending_id = ?");
    $stmt->bind_param("i", $id);

    // Execute and close
    $stmt->execute();
    $stmt->close();
    $db->close();

    echo "Deleted";
} else {
    echo "Error: Invalid request";
}
?>
