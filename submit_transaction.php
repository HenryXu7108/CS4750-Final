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
        $account_id = $_POST['incomeAccount']; 
        //var_dump($account_id); //For Debugging Purpose
        $amount = $_POST['incomeAmount']; 
        $date = $_POST['incomeDate']; 
        $source = $_POST['incomeSource']; 
       
        //1st Query: Insert an Income Record
        $query = "INSERT INTO Income (person_id, account_id, amount, date, category, source) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(1, $person_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $account_id, PDO::PARAM_INT); 
        $stmt->bindParam(3, $amount, PDO::PARAM_STR); 
        $stmt->bindParam(4, $date, PDO::PARAM_STR);
        $stmt->bindParam(5, $category, PDO::PARAM_STR);
        $stmt->bindParam(6, $source, PDO::PARAM_STR);
        $stmt->execute();

        // //2nd Query: Update the Account Balance
        // $query = "UPDATE Account SET balance=:newBalance WHERE account_id = :accountId";
        // $stmt = $db->prepare($query);
        
        // $oldAmountStmt = $db->prepare("SELECT balance FROM Account WHERE account_id = :account_id");
        // $oldAmountStmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        // $oldAmountStmt->execute();
        // $oldBalance = $oldAmountStmt->fetchAll(PDO::FETCH_ASSOC)[0]['balance'];
        // //var_dump($oldBalance); //For Debugging Purpose
        // $newBalance = (float)$oldBalance+$amount;
        // $oldAmountStmt->closeCursor();

        // $stmt->bindParam(':newBalance', $newBalance, PDO::PARAM_STR);
        // $stmt->bindParam(':accountId', $account_id, PDO::PARAM_INT);
        // $stmt->execute();
        $stmt->closeCursor();

        echo "New Income record created successfully and Account Balance Updated Succesfully";
        header('Refresh: 2; URL=dashboard.php');
    }else if($transactionType == "Expense"){
        $category = $_POST['expenseCategory']; 
        $account_id = $_POST['expenseAccount']; 
        $amount = $_POST['expenseAmount']; 
        $date = $_POST['expenseDate']; 
        $notes = $_POST['expenseNotes']; 

        $query = "INSERT INTO Spending (person_id, account_id, amount, date, category) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $person_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $account_id, PDO::PARAM_INT); 
        $stmt->bindParam(3, $amount, PDO::PARAM_STR);
        $stmt->bindParam(4, $date, PDO::PARAM_STR);
        $stmt->bindParam(5, $category, PDO::PARAM_STR);
        $stmt->execute();

        //2nd Query: Update the Account Balance
        $query = "UPDATE Account SET balance=:newBalance WHERE account_id = :accountId";
        $stmt = $db->prepare($query);
        
        $oldAmountStmt = $db->prepare("SELECT balance FROM Account WHERE account_id = :account_id");
        $oldAmountStmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $oldAmountStmt->execute();
        $oldBalance = $oldAmountStmt->fetchAll(PDO::FETCH_ASSOC)[0]['balance'];
        //var_dump($oldBalance); //For Debugging Purpose
        $newBalance = (float)$oldBalance-$amount;
        $oldAmountStmt->closeCursor();

        $stmt->bindParam(':newBalance', $newBalance, PDO::PARAM_STR);
        $stmt->bindParam(':accountId', $account_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "New Expense record created successfully and Account Balance Updated Succesfully ";
        $stmt->closeCursor();
        header('Refresh: 2; URL=dashboard.php');
    }else if ($transactionType == "Investment"){
        $account_id = $_POST["investmentAccount"];
        $investment_type = $_POST["investmentType"];
        $amount = $_POST["investmentAmount"];
        $investment_date = $_POST["investmentStartDate"];
        $maturity_date = $_POST["investmentMaturityDate"];
        $current_value = $_POST["investmentCurrentValue"];
        $rate_of_return = $_POST["investmentRateOfReturn"];
        $description = $_POST["investmentNotes"];
        
        $query = "INSERT INTO Investment (account_id, investment_type, amount_invested, investment_date, maturity_date,current_value,rate_of_return,description,person_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(1, $account_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $investment_type, PDO::PARAM_STR); 
        $stmt->bindParam(3, $amount, PDO::PARAM_STR); 
        $stmt->bindParam(4, $investment_date, PDO::PARAM_STR);
        $stmt->bindParam(5, $maturity_date, PDO::PARAM_STR);
        $stmt->bindParam(6, $current_value, PDO::PARAM_STR);
        $stmt->bindParam(7, $rate_of_return, PDO::PARAM_STR);
        $stmt->bindParam(8, $description, PDO::PARAM_STR);
        $stmt->bindParam(9, $person_id, PDO::PARAM_INT);
        
        $stmt->execute();

        //2nd Query: Update the Account Balance
        $query = "UPDATE Account SET balance=:newBalance WHERE account_id = :accountId";
        $stmt = $db->prepare($query);
        
        $oldAmountStmt = $db->prepare("SELECT balance FROM Account WHERE account_id = :account_id");
        $oldAmountStmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $oldAmountStmt->execute();
        $oldBalance = $oldAmountStmt->fetchAll(PDO::FETCH_ASSOC)[0]['balance'];
        //var_dump($oldBalance); //For Debugging Purpose
        $newBalance = (float)$oldBalance-$amount;
        $oldAmountStmt->closeCursor();

        $stmt->bindParam(':newBalance', $newBalance, PDO::PARAM_STR);
        $stmt->bindParam(':accountId', $account_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "New Investment record created successfully and Account Balance Updated Succesfully";
        $stmt->closeCursor();
        header('Refresh: 2; URL=dashboard.php');
    }else if ($transactionType == "Transaction"){
        $source_account_id = $_POST["transactionSourceAccount"];
        $destination_account_id = $_POST["transactionDestinationAccount"];
        $transaction_type = $_POST["transactionType"];
        $amount = $_POST["transactionAmount"];
        $transaction_date = $_POST["transactionDate"];
        $description = $_POST["transactionNotes"];

        #1st Query: Insert a new transaction record
        $query = "INSERT INTO Transaction (source_account_id, destination_account_id, transaction_type, amount, transaction_date,description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $source_account_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $destination_account_id, PDO::PARAM_INT); 
        $stmt->bindParam(3, $transaction_type, PDO::PARAM_STR); 
        $stmt->bindParam(4, $amount, PDO::PARAM_STR);
        $stmt->bindParam(5, $transaction_date, PDO::PARAM_STR);
        $stmt->bindParam(6, $description, PDO::PARAM_STR);
        $stmt->execute();

        //2nd Query: Update the Accounts Balance
        $query1 = "UPDATE Account SET balance=:newSourceBalance WHERE account_id = :source_account_id";
        $query2 = "UPDATE Account SET balance=:newDestinationBalance WHERE account_id = :destination_account_id";

        $sourceStmt = $db->prepare($query1);
        $destStmt = $db->prepare($query2);

        
        $sourceAmountStmt = $db->prepare("SELECT balance FROM Account WHERE account_id = :source_account_id");
        $sourceAmountStmt->bindParam(':source_account_id', $source_account_id, PDO::PARAM_INT);
        $sourceAmountStmt->execute();
        $sourceAccountAmount = $sourceAmountStmt->fetchAll(PDO::FETCH_ASSOC)[0]['balance'];
        
        $destinationAmountStmt = $db->prepare("SELECT balance FROM Account WHERE account_id = :destination_account_id");
        $destinationAmountStmt->bindParam(':destination_account_id', $destination_account_id, PDO::PARAM_INT);
        $destinationAmountStmt->execute();
        $destinationAccountAmount = $destinationAmountStmt->fetchAll(PDO::FETCH_ASSOC)[0]['balance'];
        
        $sourceNewbalance = (float)$sourceAccountAmount-$amount;
        $destinationNewbalance = (float)$destinationAccountAmount+$amount;
        
        $sourceAmountStmt->closeCursor();
        $destinationAmountStmt->closeCursor();

        $sourceStmt->bindParam(':newSourceBalance', $sourceNewbalance, PDO::PARAM_STR);
        $sourceStmt->bindParam(':source_account_id', $source_account_id, PDO::PARAM_INT);
        $destStmt->bindParam(':newDestinationBalance', $destinationNewbalance, PDO::PARAM_STR);
        $destStmt->bindParam(':destination_account_id', $destination_account_id, PDO::PARAM_INT);

        $sourceStmt->execute();
        $destStmt->execute();        
        echo "New Transaction record created successfully and Accounts Balance Updated Succesfully";
        $sourceStmt->closeCursor();
        $destStmt->closeCursor();
        header('Refresh: 2; URL=dashboard.php');
    }

}
