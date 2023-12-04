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
echo '<div><strong>Bill Notifications</strong> '. '</div>';


echo '
    </div>

</div>

</body>
</html>';
$filter1 = isset($_POST['filter1']) ? $_POST['filter1'] : 'timeClosestFirst';

echo '<div class="filter">
        <form method="post" action="billnoti.php">
            <label for="filter">Filter:</label>
            <select id="filter" name="filter1" onchange="this.form.submit()">';

$options = array(
    "amountLowHigh" => "Amount: Low to High",
    "amountHighLow" => "Amount: High to Low",
    "timeClosestFirst" => "Time: Closest First",
    "timeClosestLast" => "Time: Closest Last"
);

foreach ($options as $value => $text) {
    $selected = ($filter1 == $value) ? ' selected' : '';
    echo '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($text) . '</option>';
}

echo '    </select>
        </form>
    </div>
';

// Start with the base query
$query = "SELECT  person_id, bill_amount, due_date, description, bill_status, notification_id FROM BillNotification";

//Add conditions to the query based on the filter
switch ($filter1) {
    case "amountLowHigh":
        $query .= " ORDER BY bill_amount ASC";
        break;
    case "amountHighLow":
        $query .= " ORDER BY bill_amount DESC";
        break;
    case "timeClosestFirst":
        $query .= " ORDER BY due_date DESC";
        break;
    case "timeClosestLast":
        $query .= " ORDER BY due_date ASC";
        break;
    default:
        // If no filter is set or an unknown filter is used
        $query .= " ORDER BY due_date ASC";
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
    echo "<thead><tr><th>Amount</th><th>Due Date</th><th>Description</th><th>bill_status</th></tr></thead>";
    echo "<tbody>";
    foreach ($result as $value) {
        if($value[0] == $_SESSION['person_id']){
            echo "<form method='post' action='update_delete/bill_update.php' id= 'updateForm' onsubmit='return confirm(\"Are you sure you want to update this record?\");'>";
            echo "<tr data-id=' . htmlspecialchars($value[5]) . '>";
            echo "<td><input type='hidden' name='bill_amount' value='" . htmlspecialchars($value[1]) . "'><span contenteditable='true' class='editable' data-field='bill_amount'>" . htmlspecialchars($value[1]) . "</span></td>";
            echo "<td><input type='hidden' name='due_date' value='" . htmlspecialchars($value[2]) . "'><span contenteditable='true' class='editable' data-field='due_date'>" . htmlspecialchars($value[2]) . "</span></td>";
            echo "<td><input type='hidden' name='description' value='" . htmlspecialchars($value[3]) . "'><span contenteditable='true' class='editable' data-field='description'>" . htmlspecialchars($value[3]) . "</span></td>";

            echo "<td>";
            echo "<select name='bill_status'>";
            echo "<option value='UNPAID'" . ($value[4] == 'UNPAID' ? ' selected' : '') . ">UNPAID</option>";
            echo "<option value='PAID'" . ($value[4] == 'PAID' ? ' selected' : '') . ">PAID</option>";
            echo "<option value='OVERDUE'" . ($value[4] == 'OVERDUE' ? ' selected' : '') . ">OVERDUE</option>";
            echo "</select>";
            echo "</td>";
            echo "<input type='hidden' name='notification_id' value='" . htmlspecialchars($value[5]) . "'>";
            //$value[0] is person_id, [1] is amount, [2] is date, [3] is category, [4] is spending_id
            //person_id, bill_amount, due_date, description, bill_status, notification_id

            // Update button
            echo "<td><button type='submit' class='button update-btn'>Update</button></form></td>";

            echo '<td>';
            echo "<form method='post' action='update_delete/bill_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
            echo "<input type='hidden' name='notification_id' value='" . htmlspecialchars($value[5]) . "'>";
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
            //person_id, bill_amount, due_date, description, bill_status, notification_id
            var bill_amount = this.elements['bill_amount'].value;
            var due_date = this.elements['due_date'].value;
            var description = this.elements['description'].value;
            var bill_status = this.elements['bill_status'].value;


            var billAmountRegex = /^\d+(\.\d{0,2})?$/;
            if (!billAmountRegex.test(bill_amount)) {
                alert("Bill amount must be a number with up to two decimal places.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            // Validate date - must be in YYYY-MM-DD format
            if (!/^\d{4}-\d{2}-\d{2}$/.test(due_date)) {
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



