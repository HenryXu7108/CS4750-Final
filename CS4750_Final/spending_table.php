<?php
require ('connect-db.php');

// Set the default filter or get from POST request
$filter = isset($_POST['filter1']) ? $_POST['filter1'] : 'timeClosestFirst';

// Start with the base query
$query = "SELECT payment_method, amount, date, category FROM Spending";

// Add conditions to the query based on the filter
switch ($filter) {
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

$result = $db->query($query);

$total_spending = 0;
if ($result && $row = $result->fetch_assoc()) {
    $total_spending = $row['total_spending'];
}
if ($result->num_rows > 0) {
    // Output data of each row
    echo "<table class='table'>";
    echo "<thead><tr><th>Payment Method</th><th>Amount</th><th>Date</th><th>Category</th></tr></thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
        echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
        echo "<td><button class='button edit-btn' data-id='" . $row['spending_id'] . "'>Edit</button>";
        echo "<td><button class='button delete-btn' onclick='deleteEntry(" . $row['spending_id'] . ")'>Delete</button></td>";        echo "</tr>";
    }
    echo "</tbody></table>";
}
else {
    echo "0 results";}





// Close database connection
$db->close();
?>
<script>
    function editEntry(id) {
        // Implementation to handle edit.
        // This could be a form submission or an AJAX call to a PHP script that handles editing.
    }

    function deleteEntry(id) {
        if(confirm('Are you sure you want to delete this entry?')) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_entry.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    // On successful deletion, remove the row from the table
                    document.getElementById('row-' + id).remove();
                }
            }
            xhr.send("id=" + id);
        }
    }
</script>
