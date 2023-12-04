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

    if(!$stmt->execute()){
        exit('Error executing the account insertion');
        header('Refresh: 2; URL=addAccount.php');
    }

    $addCard = $_POST["addCard"];

    if($addCard === "YES"){
        $card_number = $_POST["cardNumber"];
        $query = "SELECT account_id FROM Account WHERE account_number = :account_number";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':account_number',$account_number,PDO::PARAM_STR);
        $stmt->execute();
        $account_id = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['account_id'];
        $cardQuery = "INSERT INTO Card (person_id,card_number,account_id) VALUES (?,?,?)";
        $stmt = $db->prepare($cardQuery);
        $stmt->bindParam(1, $person_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $card_number, PDO::PARAM_STR); 
        $stmt->bindParam(3, $account_id, PDO::PARAM_STR);
        $stmt->execute();

        echo "New Account and Card records created successfully";
    }else{
        echo "New Account record created successfully";
    }
    
    $stmt->closeCursor();
    header('Refresh: 2; URL=../dashboard.php');

}
