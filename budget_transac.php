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
echo '<div><strong>Budgt</strong> '. '</div>';


echo '
    </div>

</div>

</body>
</html>';
$filter1 = isset($_POST['filter1']) ? $_POST['filter1'] : 'amountHighLow';

echo '<div class="filter">
        <form method="post" action="budget_transac.php">
            <label for="filter">Filter:</label>
            <select id="filter" name="filter1" onchange="this.form.submit()">';

$options = array(
    "amountLowHigh" => "Amount: Low to High",
    "amountHighLow" => "Amount: High to Low",
);

foreach ($options as $value => $text) {
    $selected = ($filter1 == $value) ? ' selected' : '';
    echo '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($text) . '</option>';
}

echo '    </select>
        </form>
    </div>
';
// Set the default filter or get from POST request
//$filter = isset($_POST['filter1']) ? $_POST['filter1'] : 'timeClosestFirst';

// Start with the base query
$query = "SELECT  person_id, amount, purpose, budget_id FROM Budget";

//Add conditions to the query based on the filter
switch ($filter1) {
    case "amountLowHigh":
        $query .= " ORDER BY amount ASC";
        break;
    case "amountHighLow":
        $query .= " ORDER BY amount DESC";
        break;
    default:
        // If no filter is set or an unknown filter is used
        $query .= " ORDER BY amount DESC";
        break;
}

$stmt = $db->prepare($query);
$stmt->execute();
if (!$stmt->execute()) {
    print_r($stmt->errorInfo());
}
$result = $stmt->fetchAll();



if (count($result) > 0) {
    echo "<table class='table'>";
    echo "<thead><tr><th>Amount</th><th>Purpose</th></tr></thead>";
    echo "<tbody>";
    foreach ($result as $value) {
        if($value[0] == $_SESSION['person_id']){

            echo "<form method='post' action='update_delete/budget_update.php' id= 'updateForm' onsubmit='return confirm(\"Are you sure you want to update this record?\");'>";
            echo "<tr data-id=' . htmlspecialchars($value[3]) . '>";
            echo "<td><input type='hidden' name='amount' value='" . htmlspecialchars($value[1]) . "'><span contenteditable='true' class='editable' data-field='amount'>" . htmlspecialchars($value[1]) . "</span></td>";
            echo "<td><input type='hidden' name='purpose' value='" . htmlspecialchars($value[2]) . "'><span contenteditable='true' class='editable' data-field='purpose'>" . htmlspecialchars($value[2]) . "</span></td>";

            echo "<input type='hidden' name='budget_id' value='" . htmlspecialchars($value[3]) . "'>";
            //$value[0] is person_id, [1] is amount, [2] is purpose, [3] is budget_id

            // Update button
            echo "<td><button type='submit' class='button update-btn'>Update</button></form></td>";

            echo '<td>';
            echo "<form method='post' action='update_delete/budget_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
            echo "<input type='hidden' name='budget_id' value='" . htmlspecialchars($value[3]) . "'>";
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
            var amount = this.elements['amount'].value;
            var purpose = this.elements['purpose'].value;

            // Validate amount - must be an integer
            if (!Number.isInteger(Number(amount)) || amount < 0) {
                alert("Amount must be a positive integer.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            // Validate category - must be 255 characters or less
            if (purpose.length > 255) {
                alert("purpose must be 255 characters or less.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            return true;
        });
    });
</script>
<?php
echo'
</div>
<div class="container">
    <div class="fieldset">
        ';
echo '<div><strong>Transaction</strong> '. '</div>';


echo '
    </div>

</div>

</body>
</html>';
$filter2 = isset($_POST['filter2']) ? $_POST['filter2'] : 'amountHighLow';

echo '<div class="filter">
    <form method="post" action="budget_transac.php">
        <label for="filter">Filter:</label>
        <select id="filter" name="filter2" onchange="this.form.submit()">';

$options = array(
    "amountLowHigh" => "Amount: Low to High",
    "amountHighLow" => "Amount: High to Low",

);

foreach ($options as $value => $text) {
    $selected = ($filter2 == $value) ? ' selected' : '';
    echo '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($text) . '</option>';
}

echo '    </select>
    </form>
</div>
';

// Start with the base query
$query = "SELECT 
            source.account_number AS source_account_number,
            destination.account_number AS destination_account_number,
            t.transaction_type,
            t.amount,
            t.transaction_date,
            t.description,
            t.transaction_id
        FROM 
            Transaction t
        JOIN 
            Account source ON t.source_account_id = source.account_id
        JOIN 
            Account destination ON t.destination_account_id = destination.account_id
        WHERE 
            source.person_id = :person_id OR destination.person_id = :person_id
";

//Add conditions to the query based on the filter
switch ($filter2) {
    case "amountLowHigh":
        $query .= " ORDER BY amount ASC";
        break;
    case "amountHighLow":
        $query .= " ORDER BY amount DESC";
        break;
    default:
// If no filter is set or an unknown filter is used
        $query .= " ORDER BY amount DESC";
        break;
}

$stmt = $db->prepare($query);
$stmt->bindParam(':person_id', $_SESSION['person_id'], PDO::PARAM_INT);
$stmt->execute();
if (!$stmt->execute()) {
    print_r($stmt->errorInfo());
}
$result = $stmt->fetchAll();


if (count($result) > 0) {
    echo "<table class='table'>";
    echo "<thead><tr><th>From</th><th>To</th><th>Transaction Type</th><th>Amount</th><th>Date</th><th>Description</th></tr></thead>";
    echo "<tbody>";

    foreach ($result as $value) {
            echo "<form method='post' action='update_delete/transac_update.php' id= 'updateForm' onsubmit='return confirm(\"Are you sure you want to update this record?\");'>";
            echo "<tr data-id=' . htmlspecialchars($value[5]) . '>";

        // Non-editable fields (From, To, Transaction Type)
        echo "<td>" . htmlspecialchars($value[0]) . "</td>";
        echo "<td>" . htmlspecialchars($value[1]) . "</td>";
        echo "<td>" . htmlspecialchars($value[2]) . "</td>";

        echo "<td><input type='hidden' name='amount' value='" . htmlspecialchars($value[3]) . "'><span contenteditable='true' class='editable' data-field='amount'>" . htmlspecialchars($value[3]) . "</span></td>";
            echo "<td><input type='hidden' name='transaction_date' value='" . htmlspecialchars($value[4]) . "'><span contenteditable='true' class='editable' data-field='transaction_date'>" . htmlspecialchars($value[4]) . "</span></td>";
            echo "<td><input type='hidden' name='description' value='" . htmlspecialchars($value[5]) . "'><span contenteditable='true' class='editable' data-field='description'>" . htmlspecialchars($value[5]) . "</span></td>";

            // Hidden input for the income_id
            echo "<input type='hidden' name='transaction_id' value='" . htmlspecialchars($value[6]) . "'>";
        //    [0]source.account_number AS source_account_number,
//            [1]destination.account_number AS destination_account_number,
//            [2]t.transaction_type,
//            [3]t.amount,
//            [4]t.transaction_date,
//            [5]t.description,
//            [6]t.transaction_id

            // Update button
            echo "<td><button type='submit' class='button update-btn'>Update</button></form></td>";

            echo '<td>';
            echo "<form method='post' action='update_delete/transac_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
            echo "<input type='hidden' name='transaction_id' value='" . htmlspecialchars($value[6]) . "'>";
            echo "<button type='submit' class='button delete-btn'>Delete</button>";
            echo "</form></td>";
            echo "</tr>";

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
    //    [0]source.account_number AS source_account_number,
    //            [1]destination.account_number AS destination_account_number,
    //            [2]t.transaction_type,
    //            [3]t.amount,
    //            [4]t.transaction_date,
    //            [5]t.description,
    //            [6]t.transaction_id
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("updateForm").addEventListener("submit", function(e) {

            var amount = this.elements['amount'].value;
            var transaction_date = this.elements['transaction_date'].value;
            var description = this.elements['description'].value;
            // Validate amount - must be an integer
            if (!amount.match(/^\d+(\.\d{1,2})?$/) || parseFloat(balance) < 0) {
                alert("Balance must be a positive number with up to two decimal places.");
                e.preventDefault();
                return false;
            }

            // Validate date - must be in YYYY-MM-DD format
            if (!/^\d{4}-\d{2}-\d{2}$/.test(transaction_date)) {
                alert("Date must be in YYYY-MM-DD format.");
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
echo '<div><strong>Account</strong> '. '</div>';


echo '
    </div>

</div>

</body>
</html>';


// Set the default filter or get from POST request
//$filter = isset($_POST['filter1']) ? $_POST['filter1'] : 'timeClosestFirst';

// Start with the base query
$query = "SELECT  person_id, account_number, account_type, balance, account_status, account_id FROM Account";


$stmt = $db->prepare($query);
$stmt->execute();
if (!$stmt->execute()) {
print_r($stmt->errorInfo());
}
$result = $stmt->fetchAll();


if (count($result) > 0) {
echo "<table class='table'>";
    echo "<thead><tr><th>Account Number</th><th>Balance</th><th>Account Type</th><th>Account Status</th></tr></thead>";
    echo "<tbody>";
    foreach ($result as $value) {
    if($value[0] == $_SESSION['person_id']){

    echo "<form method='post' action='update_delete/account_update.php' id= 'updateForm' onsubmit='return confirm(\"Are you sure you want to update this record?\");'>";
        echo "<tr data-id=' . htmlspecialchars($value[5]) . '>";
            echo "<td><input type='hidden' name='account_number' value='" . htmlspecialchars($value[1]) . "'><span contenteditable='true' class='editable' data-field='account_number'>" . htmlspecialchars($value[1]) . "</span></td>";
            echo "<td><input type='hidden' name='balance' value='" . htmlspecialchars($value[3]) . "'><span contenteditable='true' class='editable' data-field='balance'>" . htmlspecialchars($value[3]) . "</span></td>";
            //Account_type dropdown
            echo "<td>";
            echo "<select name='account_type'>";
            echo "<option value='CHECKING'" . ($value[2] == 'CHECKING' ? ' selected' : '') . ">Checking</option>";
            echo "<option value='SAVINGS'" . ($value[2] == 'SAVINGS' ? ' selected' : '') . ">Savings</option>";
            echo "<option value='CREDIT'" . ($value[2] == 'CREDIT' ? ' selected' : '') . ">Credit</option>";
            echo "</select>";
            echo "</td>";
            // Account status dropdown
            echo "<td>";
            echo "<select name='account_status'>";
            echo "<option value='ACTIVE'" . ($value[4] == 'ACTIVE' ? ' selected' : '') . ">Active</option>";
            echo "<option value='INACTIVE'" . ($value[4] == 'INACTIVE' ? ' selected' : '') . ">Inactive</option>";
            echo "<option value='CLOSED'" . ($value[4] == 'CLOSED' ? ' selected' : '') . ">Closed</option>";
            echo "</select>";
            echo "</td>";
            // Hidden input for the account_id
            echo "<input type='hidden' name='account_id' value='" . htmlspecialchars($value[5]) . "'>";
            //person_id, account number, account type, balance, status, account_id

            // Update button
            echo "<td><button type='submit' class='button update-btn'>Update</button></form></td>";

    echo '<td>';
        echo "<form method='post' action='update_delete/account_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
            echo "<input type='hidden' name='account_id' value='" . htmlspecialchars($value[5]) . "'>";
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
        //account_number, account_type, balance, account_status
        document.getElementById("updateForm").addEventListener("submit", function(e) {
            var amount = this.elements['account_number'].value;
            var date = this.elements['account_type'].value;
            var category = this.elements['balance'].value;
            var source = this.elements['account_status'].value;

            // Validate amount - must be an integer

            if (!balance.match(/^\d+(\.\d{1,2})?$/) || parseFloat(balance) < 0) {
                alert("Balance must be a positive number with up to two decimal places.");
                e.preventDefault();
                return false;
            }


            // Validate category - must be 255 characters or less
            if (account_number.length > 255) {
                alert("account_number must be 255 characters or less.");
                e.preventDefault(); // prevent form submission
                return false;
            }
            if (source.length > 255) {
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


