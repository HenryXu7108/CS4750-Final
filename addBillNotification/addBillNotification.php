<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add a BillNotification</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add a BillNotification</h1>
    <form id="BillNotificationForm" action="submit_addBillNotification.php" method="post">  
        <label for="billerName">Biller Name:</label>
        <input type="text" name="billerName">
        <label for="billAmount">Bill Amount:</label>
        <input type="number" name="billAmount">
        <label for="dueDate">Due Date:</label>
        <input type="date" name="dueDate">
        <label for="billerStatus">Biller Status:</label>
        <select name="billerStatus" id="billerStatus">
            <option value="UNPAID">Unpaid</option>
            <option value="PAID">Paid</option>
            <option value="OVERDUE">Overdue</option>
        </select>
        <textarea name="notes" placeholder="Notes"></textarea>
        <button type="submit" id="submitBtn">Submit</button>
    </form>

</body>
</html>
