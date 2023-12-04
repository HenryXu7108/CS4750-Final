<?php
session_start();
require_once('../connect-db.php');

global $db;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = (int)$_SESSION['person_id'];
    $amount =  $_POST["Amount"];
    $purpose = $_POST["budgetPurpose"];

    $query = "INSERT INTO Budget (person_id, purpose, amount) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(1, $person_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $purpose, PDO::PARAM_STR); 
    $stmt->bindParam(3, $amount, PDO::PARAM_STR);

    $stmt->execute();
    echo "New Budget record created successfully";
    $stmt->closeCursor();
    header('Refresh: 2; URL=../dashboard.php');
}
