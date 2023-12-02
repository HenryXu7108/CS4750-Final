<?php
session_start();
require_once('../connect-db.php');

global $db;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = (int)$_SESSION['person_id'];
    $biller_name = $_POST["billerName"];
    $bill_amount =  $_POST["billAmount"];
    $due_date =  $_POST["dueDate"];
    $description =  $_POST["notes"];
    $bill_status = $_POST["billerStatus"];

    $query = "INSERT INTO BillNotification (person_id, biller_name, bill_amount, due_date, description, bill_status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(1, $person_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $biller_name, PDO::PARAM_STR); 
    $stmt->bindParam(3, $bill_amount, PDO::PARAM_STR);
    $stmt->bindParam(4, $due_date, PDO::PARAM_STR);
    $stmt->bindParam(5, $description, PDO::PARAM_STR);
    $stmt->bindParam(6, $bill_status, PDO::PARAM_STR);

    $stmt->execute();
    echo "New BillNotification record created successfully";
    $stmt->closeCursor();
    header('Refresh: 2; URL=../dashboard.php');
}
