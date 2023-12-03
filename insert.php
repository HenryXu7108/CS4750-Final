<?php 
session_start();
$person_id = $_SESSION['person_id'];
require_once('connect-db.php');

$stmt = $db->prepare("SELECT account_id, account_number, account_type FROM Account WHERE person_id = :person_id");
$stmt->bindParam(':person_id', $person_id, PDO::PARAM_INT);
$stmt->execute();

$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add a Transaction</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />

    <style>
        .fields, #submitBtn {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed top" aria-label="Main Navigation Bar">
        <div>
            <a class="navbar-brand" href="dashboard.php" style="padding-left: 20px; text-align: center">Financial Tracker</a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" style="justify-content: right" id="navbarCollapse">
            <ul class="navbar-nav mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="dashboard.php">DashBoard</a>
                </li>

                
                <li class="nav-item">
                    <a href="addAccount/addAccount.php" class="nav-link">Add Account</a>
                </li>

                <li class="nav-item">
                    <a href="addBillNotification/addBillNotification.php" class="nav-link">Add BillNotification</a>
                </li>

                <li class="nav-item">
                    <a href="main.html" class="nav-link ">View Records</a>
                </li>

                <li class="nav-item">
                    <a href="logout.php" class="nav-link ">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>
    <h1>Add a Transaction</h1>

    <form id="transactionForm" action="submit_transaction.php" method="post">  
        <label for="transactionsType">Transaction Type:</label>
        <select name="transactionsType" id="transactionsType">
            <option value="">Select a Type</option>
            <option value="Income">Income</option>
            <option value="Expense">Expense</option>
            <option value="Investment">Investment</option>
            <option value="Transaction">Transaction</option>
        </select>

        <div id="incomeFields" class="fields">
            <label for="incomeCategory">Category:</label>
            <select name="incomeCategory" id="incomeCategory">
                <option value="Salary">Salary</option>
                <option value="Part-Time">Part-Time</option>
                <option value="Bonus">Bonus</option>
            </select>
            <label for="incomeAccount">Account:</label>
            <select name="incomeAccount" id="incomeAccount">
                <?php foreach ($accounts as $account): ?>
                    <option value="<?= htmlspecialchars($account['account_id']) ?>">
                        <?= htmlspecialchars($account['account_number']) . " (" . htmlspecialchars($account['account_type']) . ")" ?>
                    </option>
                <?php endforeach; ?>
             </select>
            <label for="incomeAmount">Amount:</label>
            <input type="number" name="incomeAmount">
            <label for="incomeDate">Date:</label>
            <input type="date" name="incomeDate">
            <label for="Source">Source:</label>
            <input type="text" name="incomeSource">
            <textarea name="incomeNotes" placeholder="Notes"></textarea>
        </div>

        <div id="expenseFields" class="fields">
            <label for="expenseCategory">Category:</label>
            <select name="expenseCategory" id="expenseCategory">
                <option value="Household">Household goods</option>
                <option value="FoodAndDrinks">Food and Drinks</option>
                <option value="Living">Living Expenses</option>
                <option value="Traffic">Traffic Expenses</option>
                <option value="Child">Children Expenses</option>
            </select>
            <label for="expenseAccount">Account:</label>
            <select name="expenseAccount" id="expenseAccount">
                <?php foreach ($accounts as $account): ?>
                    <option value="<?= htmlspecialchars($account['account_id']) ?>">
                        <?= htmlspecialchars($account['account_number']) . " (" . htmlspecialchars($account['account_type']) . ")" ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="expenseAmount">Amount:</label>
            <input type="number" name="expenseAmount">
            <label for="expenseDate">Date:</label>
            <input type="date" name="expenseDate">
            <textarea name="expenseNotes" placeholder="Notes"></textarea>
        </div>
        <div id="investmentFields" class="fields">
            <label for="investmentAccount">Account:</label>
            <select name="investmentAccount" id="investmentAccount">
                <?php foreach ($accounts as $account): ?>
                    <option value="<?= htmlspecialchars($account['account_id']) ?>">
                        <?= htmlspecialchars($account['account_number']) . " (" . htmlspecialchars($account['account_type']) . ")" ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="investmentType">Investment Type:</label>
            <select name="investmentType" id="investmentType">
                <option value="STOCKS">Stocks</option>
                <option value="BONDS">Bonds</option>
                <option value="REAL ESTATE">Real Estate</option>
                <option value="MUTUAL FUNDS">Mutual Funds</option>
                <option value="OTHER">Other</option>
            </select>
            <label for="investmentAmount">Investment Amount:</label>
            <input type="number" id="investmentAmount" name="investmentAmount" placeholder="Investment Amount">
            <label for="investmentStartDate">investment Start Date:</label>
            <input type="date" id="investmentStartDate" name="investmentStartDate">
            <label for="investmentMaturityDate">investment Maturity Date:</label>
            <input type="date" id="investmentMaturityDate" name="investmentMaturityDate">
            <label for="investmentCurrentValue">Current Value:</label>
            <input type="number" id="investmentCurrentValue" name="investmentCurrentValue" placeholder="Current Value">
            <label for="investmentRateOfReturn">Rate Of Return:</label>
            <input type="number" id="investmentRateOfReturn" name="investmentRateOfReturn" placeholder="Rate of Return">
            <textarea name="investmentNotes" placeholder="Notes"></textarea>
        </div>
        <div id="transactionFields" class="fields">
            <label for="transactionSourceAccount">Source Account:</label>
            <select name="transactionSourceAccount" id="transactionSourceAccount">
                <?php foreach ($accounts as $account): ?>
                    <option value="<?= htmlspecialchars($account['account_id']) ?>">
                        <?= htmlspecialchars($account['account_number']) . " (" . htmlspecialchars($account['account_type']) . ")" ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="transactionDestinationAccount">Destination Account:</label>
            <select name="transactionDestinationAccount" id="transactionDestinationAccount">
                <?php foreach ($accounts as $account): ?>
                    <option value="<?= htmlspecialchars($account['account_id']) ?>">
                        <?= htmlspecialchars($account['account_number']) . " (" . htmlspecialchars($account['account_type']) . ")" ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="transactionType">Transaction Type:</label>
            <select name="transactionType" id="transactionType">
                <option value="DEPOSIT">DEPOSIT</option>
                <option value="TRANSFER">TRANSFER</option>
                <option value="RETRIEVAL">RETRIEVAL</option>
            </select>
            <label for="transactionAmount">Transaction Amount</label>
            <input type="number" name="transactionAmount" placeholder="Amount">
            <label for="transactionDate">Transaction Date</label>
            <input type="date" name="transactionDate">
            <textarea name="transactionNotes" placeholder="Notes"></textarea>
        </div>
        <button type="submit" id="submitBtn">Submit</button>
    </form>

    <script src="script.js"></script>
</body>
</html>
