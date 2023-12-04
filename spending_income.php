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
echo '<div><strong>Spending</strong> '. '</div>';


echo '
    </div>

</div>

</body>
</html>';
$filter1 = isset($_POST['filter1']) ? $_POST['filter1'] : 'timeClosestFirst';

echo '<div class="filter">
        <form method="post" action="spending_income.php">
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
$query = "SELECT  person_id, amount, date, category, spending_id FROM Spending";

 //Add conditions to the query based on the filter
switch ($filter1) {
    case "amountLowHigh":
        $query .= " ORDER BY amount ASC";
        break;
    case "amountHighLow":
        $query .= " ORDER BY amount DESC";
        break;
    case "timeClosestFirst":
        $query .= " ORDER BY date ASC";
        break;
    case "timeClosestLast":
        $query .= " ORDER BY date DESC";
        break;
    default:
        // If no filter is set or an unknown filter is used
        $query .= " ORDER BY date ASC";
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
    echo "<thead><tr><th>Amount</th><th>Date</th><th>Category</th></tr></thead>";
    echo "<tbody>";
    foreach ($result as $value) {
        if($value[0] == $_SESSION['person_id']){

            echo "<form method='post' action='update_delete/update.php' id= 'updateForm' onsubmit='return confirm(\"Are you sure you want to update this record?\");'>";
            echo "<tr data-id=' . htmlspecialchars($value[4]) . '>";
            echo "<td><input type='hidden' name='amount' value='" . htmlspecialchars($value[1]) . "'><span contenteditable='true' class='editable' data-field='amount'>" . htmlspecialchars($value[1]) . "</span></td>";
            echo "<td><input type='hidden' name='date' value='" . htmlspecialchars($value[2]) . "'><span contenteditable='true' class='editable' data-field='date'>" . htmlspecialchars($value[2]) . "</span></td>";
            echo "<td><input type='hidden' name='category' value='" . htmlspecialchars($value[3]) . "'><span contenteditable='true' class='editable' data-field='category'>" . htmlspecialchars($value[3]) . "</span></td>";

            // Hidden input for the spending_id
            echo "<input type='hidden' name='spending_id' value='" . htmlspecialchars($value[4]) . "'>";
            //$value[0] is person_id, [1] is amount, [2] is date, [3] is category, [4] is spending_id

            // Update button
            echo "<td><button type='submit' class='button update-btn'>Update</button></form></td>";

            echo '<td>';
            echo "<form method='post' action='update_delete/delete_entry.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
            echo "<input type='hidden' name='spending_id' value='" . htmlspecialchars($value[4]) . "'>";
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
            var date = this.elements['date'].value;
            var category = this.elements['category'].value;

            // Validate amount - must be an integer
            if (!Number.isInteger(Number(amount)) || amount < 0) {
                alert("Amount must be a positive integer.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            // Validate date - must be in YYYY-MM-DD format
            if (!/^\d{4}-\d{2}-\d{2}$/.test(date)) {
                alert("Date must be in YYYY-MM-DD format.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            // Validate category - must be 255 characters or less
            if (category.length > 255) {
                alert("Category must be 255 characters or less.");
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
        echo '<div><strong>Income</strong> '. '</div>';


        echo '
    </div>

</div>

</body>
</html>';
$filter2 = isset($_POST['filter2']) ? $_POST['filter2'] : 'timeClosestFirst';

echo '<div class="filter">
    <form method="post" action="spending_income.php">
        <label for="filter">Filter:</label>
        <select id="filter" name="filter2" onchange="this.form.submit()">';

            $options = array(
            "amountLowHigh" => "Amount: Low to High",
            "amountHighLow" => "Amount: High to Low",
            "timeClosestFirst" => "Time: Closest First",
            "timeClosestLast" => "Time: Closest Last"
            );

            foreach ($options as $value => $text) {
            $selected = ($filter2 == $value) ? ' selected' : '';
            echo '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($text) . '</option>';
            }

            echo '    </select>
    </form>
</div>
';
// Set the default filter or get from POST request
//$filter = isset($_POST['filter1']) ? $_POST['filter1'] : 'timeClosestFirst';

// Start with the base query
$query = "SELECT  person_id, amount, date, category, source, income_id FROM Income";

//Add conditions to the query based on the filter
switch ($filter2) {
case "amountLowHigh":
$query .= " ORDER BY amount ASC";
break;
case "amountHighLow":
$query .= " ORDER BY amount DESC";
break;
case "timeClosestFirst":
$query .= " ORDER BY date ASC";
break;
case "timeClosestLast":
$query .= " ORDER BY date DESC";
break;
default:
// If no filter is set or an unknown filter is used
$query .= " ORDER BY date ASC";
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
    echo "<thead><tr><th>Amount</th><th>Date</th><th>Category</th><th>Source</th</tr></thead>";
    echo "<tbody>";
    foreach ($result as $value) {
    if($value[0] == $_SESSION['person_id']){

    echo "<form method='post' action='update_delete/income_update.php' id= 'updateForm' onsubmit='return confirm(\"Are you sure you want to update this record?\");'>";
        echo "<tr data-id=' . htmlspecialchars($value[5]) . '>";
            echo "<td><input type='hidden' name='amount' value='" . htmlspecialchars($value[1]) . "'><span contenteditable='true' class='editable' data-field='amount'>" . htmlspecialchars($value[1]) . "</span></td>";
            echo "<td><input type='hidden' name='date' value='" . htmlspecialchars($value[2]) . "'><span contenteditable='true' class='editable' data-field='date'>" . htmlspecialchars($value[2]) . "</span></td>";
            echo "<td><input type='hidden' name='category' value='" . htmlspecialchars($value[3]) . "'><span contenteditable='true' class='editable' data-field='category'>" . htmlspecialchars($value[3]) . "</span></td>";
            echo "<td><input type='hidden' name='source' value='" . htmlspecialchars($value[4]) . "'><span contenteditable='true' class='editable' data-field='source'>" . htmlspecialchars($value[4]) . "</span></td>";

            // Hidden input for the income_id
            echo "<input type='hidden' name='income_id' value='" . htmlspecialchars($value[5]) . "'>";
            //$value[0] is person_id, [1] is amount, [2] is date, [3] is category, [4] is source, [5] is income_id

            // Update button
            echo "<td><button type='submit' class='button update-btn'>Update</button></form></td>";

    echo '<td>';
        echo "<form method='post' action='update_delete/income_delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
            echo "<input type='hidden' name='income_id' value='" . htmlspecialchars($value[5]) . "'>";
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
            var date = this.elements['date'].value;
            var category = this.elements['category'].value;
            var source = this.elements['source'].value;

            // Validate amount - must be an integer
            if (!Number.isInteger(Number(amount)) || amount < 0) {
                alert("Amount must be a positive integer.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            // Validate date - must be in YYYY-MM-DD format
            if (!/^\d{4}-\d{2}-\d{2}$/.test(date)) {
                alert("Date must be in YYYY-MM-DD format.");
                e.preventDefault(); // prevent form submission
                return false;
            }

            // Validate category - must be 255 characters or less
            if (category.length > 255) {
                alert("Category must be 255 characters or less.");
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


