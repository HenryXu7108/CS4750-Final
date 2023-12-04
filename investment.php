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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
    <title>Finance Tracker</title>

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
echo '<div><strong>Investment</strong> '. '</div>';

echo '
    </div>

</div>

';

// Start with the base query
$query = "SELECT I.person_id, investment_type, amount_invested, investment_date, maturity_date, current_value, rate_of_return, description, A.account_number, investment_id 
FROM Investment I JOIN Account A ON A.account_id=I.account_id;
          ";


$stmt = $db->prepare($query);
$stmt->execute();
if (!$stmt) {
    print_r($stmt->errorInfo());
}
$result = $stmt->fetchAll();


//person_id, investment_type, amount_invested, investment_date, maturity_date, current_value, rate_of_return, description, A.account_number, investment_id
//0           1                    2               3               4               5                 6             7          8                 9
if (count($result) > 0) {
    echo "<table class='table'>";
    echo "<thead><tr><th>investment_type</th><th>amount_invested</th><th>investment_date</th><th>maturity_date</th><th>current_value</th><th>rate_of_return</th><th>description</th></tr></thead>";
    echo "<tbody>";
    foreach ($result as $value) {
        if($value[0] == $_SESSION['person_id']){
            echo "<form method='post' action='update_delete/invest_update.php' id= 'updateForm' onsubmit='return confirm(\"Are you sure you want to update this record?\");'>";
            echo "<tr data-id=' . htmlspecialchars($value[9]) . '>";
            //investment_type dropdown
            echo "<td>";
            echo "<select name='investment_type'>";
            echo "<option value='STOCKS'" . ($value[1] == 'STOCKS' ? ' selected' : '') . ">STOCKS</option>";
            echo "<option value='BONDS'" . ($value[1] == 'BONDS' ? ' selected' : '') . ">BONDS</option>";
            echo "<option value='REAL ESTATE'" . ($value[1] == 'REAL ESTATE' ? ' selected' : '') . ">REAL ESTATE</option>";
            echo "<option value='MUTUAL FUNDS'" . ($value[1] == 'MUTUAL FUNDS' ? ' selected' : '') . ">MUTUAL FUNDS</option>";
            echo "<option value='OTHER'" . ($value[1] == 'OTHER' ? ' selected' : '') . ">OTHER</option>";

            echo "</select>";
            echo "</td>";

            echo "<td><input type='hidden' name='amount_invested' value='" . htmlspecialchars($value[2]) . "'><span contenteditable='true' class='editable' data-field='amount_invested'>" . htmlspecialchars($value[2]) . "</span></td>";
            echo "<td><input type='hidden' name='investment_date' value='" . htmlspecialchars($value[3]) . "'><span contenteditable='true' class='editable' data-field='investment_date'>" . htmlspecialchars($value[3]) . "</span></td>";
            echo "<td><input type='hidden' name='maturity_date' value='" . htmlspecialchars($value[4]) . "'><span contenteditable='true' class='editable' data-field='maturity_date'>" . htmlspecialchars($value[4]) . "</span></td>";
            echo "<td><input type='hidden' name='current_value' value='" . htmlspecialchars($value[5]) . "'><span contenteditable='true' class='editable' data-field='current_value'>" . htmlspecialchars($value[5]) . "</span></td>";
            echo "<td><input type='hidden' name='rate_of_return' value='" . htmlspecialchars($value[6]) . "'><span contenteditable='true' class='editable' data-field='rate_of_return'>" . htmlspecialchars($value[6]) . "</span></td>";
            echo "<td><input type='hidden' name='description' value='" . htmlspecialchars($value[7]) . "'><span contenteditable='true' class='editable' data-field='description'>" . htmlspecialchars($value[7]) . "</span></td>";


            echo "<input type='hidden' name='investment_id' value='" . htmlspecialchars($value[9]) . "'>";

            // Update button
            echo "<td><button type='submit' class='button update-btn'>Update</button></form></td>";

            echo '<td>';
            echo "<form method='post' action='update_delete/invest_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
            echo "<input type='hidden' name='investment_id' value='" . htmlspecialchars($value[9]) . "'>";
            echo "<button type='submit' class='button delete-btn'>Delete</button>";
            echo "</form></td>";
            echo "</tr>";
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
            //investment_type, amount_invested, investment_date, maturity_date, current_value, rate_of_return, description

            var amount_invested = this.elements['amount_invested'].value;
            var rate_of_return = this.elements['rate_of_return'].value;
            var investment_date = this.elements['investment_date'].value;
            var maturity_date = this.elements['maturity_date'].value;
            var current_value = this.elements['current_value'].value;
            var description = this.elements['description'].value;


            var decimal15_2Regex = /^\d{1,13}(\.\d{1,2})?$/;
            var decimal5_2Regex = /^\d{1,3}(\.\d{1,2})?$/;

            if (!decimal15_2Regex.test(amount_invested) || !decimal15_2Regex.test(current_value)) {
                alert("Amount invested and current value must be numbers with up to 13 digits and 2 decimal places.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            if (!decimal5_2Regex.test(rate_of_return)) {
                alert("Rate of return must be a number with up to 3 digits and 2 decimal places.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            // Validate date - must be in YYYY-MM-DD format
            if (!/^\d{4}-\d{2}-\d{2}$/.test(investment_date)) {
                alert("investment_date must be in YYYY-MM-DD format.");
                e.preventDefault(); // prevent form submission
                return false;
            }
            if (!/^\d{4}-\d{2}-\d{2}$/.test(maturity_date)) {
                alert("maturity_date must be in YYYY-MM-DD format.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            // Validate category - must be 255 characters or less
            if (description.length > 255) {
                alert("Category must be 255 characters or less.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            return true;
        });
    });
</script>

<?php
echo'</body>
</html>';
$stmt->closeCursor();

// Close database connection

//?>


