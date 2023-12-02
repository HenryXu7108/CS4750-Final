<?php
session_start();
require_once('connect-db.php');

global $db;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $transactionType = $_POST["transactionsType"];
    
    if (empty($transactionType)) {
        exit('Error with the transaction form');
        header('Refresh: 2; URL=dashboard.php');
    }

    $person_id = (int)$_SESSION['person_id'];
    if ($transactionType === "Income"){
        $category = $_POST['incomeCategory']; 
        $account = $_POST['incomeAccount']; 
        $amount = $_POST['incomeAmount']; 
        $date = $_POST['incomeDate']; 
        $source = $_POST['incomeSource']; 
       
        
        $query = "INSERT INTO Income (person_id, amount, date, category, source) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(1, $person_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $amount, PDO::PARAM_STR); 
        $stmt->bindParam(3, $date, PDO::PARAM_STR);
        $stmt->bindParam(4, $category, PDO::PARAM_STR);
        $stmt->bindParam(5, $source, PDO::PARAM_STR);
       
        $stmt->execute();
        echo "New Income record created successfully";
        $stmt->closeCursor();
        header('Refresh: 2; URL=dashboard.php');
    }else if($transactionType == "Expense"){
        $category = $_POST['expenseCategory']; 
        $account = $_POST['expenseAccount']; 
        $amount = $_POST['expenseAmount']; 
        $date = $_POST['expenseDate']; 
        $notes = $_POST['expenseNotes']; 

        $query = "INSERT INTO Spending (person_id, payment_method, amount, date, category) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $person_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $account, PDO::PARAM_STR); 
        $stmt->bindParam(3, $amount, PDO::PARAM_STR);
        $stmt->bindParam(4, $date, PDO::PARAM_STR);
        $stmt->bindParam(5, $category, PDO::PARAM_STR);
       
        $stmt->execute();
        echo "New Expense record created successfully";
        $stmt->closeCursor();
        header('Refresh: 2; URL=dashboard.php');
    }else if ($transactionType == "Investment"){
        $investment_method = $_POST["investmentAccount"];
        $investment_type = $_POST["investmentType"];
        $amount_invested = $_POST["investmentAmount"];
        $investment_date = $_POST["investmentStartDate"];
        $maturity_date = $_POST["investmentMaturityDate"];
        $current_value = $_POST["investmentCurrentValue"];
        $rate_of_return = $_POST["investmentRateOfReturn"];
        $description = $_POST["investmentNotes"];
        
        $query = "INSERT INTO Investment (investment_method, investment_type, amount_invested, investment_date, maturity_date,current_value,rate_of_return,description,person_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(1, $investment_method, PDO::PARAM_STR);
        $stmt->bindParam(2, $investment_type, PDO::PARAM_STR); 
        $stmt->bindParam(3, $amount_invested, PDO::PARAM_STR); 
        $stmt->bindParam(4, $investment_date, PDO::PARAM_STR);
        $stmt->bindParam(5, $maturity_date, PDO::PARAM_STR);
        $stmt->bindParam(6, $current_value, PDO::PARAM_STR);
        $stmt->bindParam(7, $rate_of_return, PDO::PARAM_STR);
        $stmt->bindParam(8, $description, PDO::PARAM_STR);
        $stmt->bindParam(9, $person_id, PDO::PARAM_INT);
        
        $stmt->execute();
        echo "New Investment record created successfully";
        $stmt->closeCursor();
        header('Refresh: 2; URL=dashboard.php');
    }else if ($transactionType == "Transaction"){
        

    }

}
