<?php
session_start();
require_once('../connect-db.php');

global $db;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = (int)$_SESSION['person_id'];
    $account_number = $_POST["accountNumber"];
    $account_type =  $_POST["accountType"];
    $balance =  $_POST["balance"];
    $account_status =  $_POST["accountStatus"];
    $branch_name = $_POST["accountBranchName"];

    $query = "INSERT INTO Account (person_id, account_number, account_type, balance, account_status,branch_name) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(1, $person_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $account_number, PDO::PARAM_STR); 
    $stmt->bindParam(3, $account_type, PDO::PARAM_STR);
    $stmt->bindParam(4, $balance, PDO::PARAM_STR);
    $stmt->bindParam(5, $account_status, PDO::PARAM_STR);
    $stmt->bindParam(6, $branch_name, PDO::PARAM_STR);

    $stmt->execute();
    echo "New Account record created successfully";
    $stmt->closeCursor();
    header('Refresh: 2; URL=../dashboard.php');
}
