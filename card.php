<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require ('connect-db.php');
require_once ('auth_functions.php');

echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="your name">
    <meta name="description" content="include some description about your page">

    <title>Welcome to CS4750 Financial Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />

</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed top" aria-label="Main Navigation Bar">
    <div>
        <a class="navbar-brand" href="dashboard.php" style="padding-left: 20px; text-align: center">Financial Tracker</a>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" style="justify-content: right" id="navbarCollapse">
        <ul class="navbar-nav mb-2 mb-md-0">
            <li class="nav-item">
                <a class="nav-link " aria-current="page" href="dashboard.php">DashBoard</a>
            </li>

            <li class="nav-item">
                <a href="insert.php" class="nav-link ">Insert Records</a>
            </li>

            <li class="nav-item">
                <a href="addAccount/addAccount.php" class="nav-link">Add Account</a>
            </li>

            <li class="nav-item">
                <a href="addBillNotification/addBillNotification.php" class="nav-link">Add BillNotification</a>
            </li>

            <li class="nav-item">
                <a href="addBudget/addBudget.php" class="nav-link">Add Budget</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    View Records
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="spending_income.php">Spending & Income</a></li>
                    <li><a class="dropdown-item" href="budget_transac.php">Budget/Transaction/Account</a></li>
                    <li><a class="dropdown-item" href="investment.php">Investment</a></li>
                    <li><a class="dropdown-item" href="card.php">Cards</a></li>
                    <li><a class="dropdown-item" href="billnoti.php">Bill Notification</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="logout.php" class="nav-link ">Log Out</a>
            </li>
        </ul>
    </div>
</nav>
<link rel="stylesheet" href="css/main.css">
<div class="container">
';
echo '<h1>Welcome, '. $_SESSION['name']. '!'. '</h1>';
echo'
</div>
<div class="container">
    <div class="fieldset">
';
echo '<div><strong>Card</strong> '. '</div>';


$query = "SELECT 
    c.person_id,
    c.card_id,
    c.card_number,
    a.account_number,
    CASE
        WHEN cc.card_id IS NOT NULL THEN 'Credit'
        WHEN dc.card_id IS NOT NULL THEN 'Debit'
    END AS card_type,
    cc.credit_limit,
    cc.interest_rate
FROM 
    Card c
LEFT JOIN 
    Credit_Card cc ON c.card_id = cc.card_id
LEFT JOIN 
    Debit_Card dc ON c.card_id = dc.card_id
JOIN 
    Account a ON c.account_id = a.account_id;
";


$stmt = $db->prepare($query);
$stmt->bindParam(':person_id', $_SESSION['person_id'], PDO::PARAM_INT);
$stmt->execute();
if (!$stmt->execute()) {
    print_r($stmt->errorInfo());
}
$result = $stmt->fetchAll();


if (count($result) > 0) {
    echo "<table class='table'>";
    echo "<thead><tr><th>Card Number</th><th>Account Number</th><th>Type</th><th>Credit Limit</th><th>Interest Rate</th></tr></thead>";
    echo "<tbody>";
    foreach ($result as $value) {
        if($value[0] == $_SESSION['person_id']){
            if($value[4] == 'Credit'){
                echo "<form method='post' action='update_delete/card_update.php' id= 'updateForm' onsubmit='return confirm(\"Are you sure you want to update this record?\");'>";
                echo "<tr data-id=' . htmlspecialchars($value[1]) . '>";
                echo "<td>" . htmlspecialchars($value[2]) . "</td>";
                echo "<td>" . htmlspecialchars($value[3]) . "</td>";
                echo "<td>" . htmlspecialchars($value[4]) . "</td>";
                echo "<td><input type='hidden' name='credit_limit' value='" . htmlspecialchars($value[5]) . "'><span contenteditable='true' class='editable' data-field='credit_limit'>" . htmlspecialchars($value[5]) . "</span></td>";
                echo "<td><input type='hidden' name='interest_rate' value='" . htmlspecialchars($value[6]) . "'><span contenteditable='true' class='editable' data-field='interest_rate'>" . htmlspecialchars($value[6]) . "</span></td>";

                echo "<input type='hidden' name='card_id' value='" . htmlspecialchars($value[1]) . "'>";

                // Update button
                echo "<td><button type='submit' class='button update-btn'>Update</button></form></td>";

                echo '<td>';
                echo "<form method='post' action='update_delete/card_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
                echo "<input type='hidden' name='card_id' value='" . htmlspecialchars($value[1]) . "'>";
                echo "<button type='submit' class='button delete-btn'>Delete</button>";
                echo "</form></td>";
                echo "</tr>";
            }
            else{
                echo "<tr data-id=' . htmlspecialchars($value[1]) . '>";
                echo "<td>" . htmlspecialchars($value[2]) . "</td>";
                echo "<td>" . htmlspecialchars($value[3]) . "</td>";
                echo "<td>" . htmlspecialchars($value[4]) . "</td>";

                echo "<input type='hidden' name='card_id' value='" . htmlspecialchars($value[1]) . "'>";


                echo '<td>';
                echo "<form method='post' action='update_delete/card_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
                echo "<input type='hidden' name='card_id' value='" . htmlspecialchars($value[1]) . "'>";
                echo "<button type='submit' class='button delete-btn'>Delete</button>";
                echo "</form></td>";
                echo "</tr>";

            }

        }
    }
    echo "</tbody></table>";


} else {
    echo "0 results";}

?>
<script>
    document.querySelectorAll('.editable').forEach(function(element) {
        element.addEventListener('blur', function() {
            var hiddenInput = this.previousElementSibling; // Assuming the hidden input is right before the editable span
            hiddenInput.value = this.innerText.trim();
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("updateForm").addEventListener("submit", function(e) {
            var credit_limit = this.elements['credit_limit'].value;
            var interest_rate = this.elements['interest_rate'].value;

            var doubleRegex = /^\d*(\.\d+)?$/; // Simple regex for double
            var decimal10_0Regex = /^\d{1,10}$/; // Regex for up to 10 digits, no decimal places

            if (!doubleRegex.test(credit_limit)) {
                alert("Credit limit must be a valid number.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            if (!decimal10_0Regex.test(interest_rate)) {
                alert("Interest rate must be a number with up to 10 digits and no decimal places.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            return true;
        });
    });
</script>





