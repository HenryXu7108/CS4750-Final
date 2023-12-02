<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add an Account</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add an Account</h1>
    <form id="AccountForm" action="submit_addAccount.php" method="post">  
        <label for="accountBranchName">Branch Name:</label>
        <input type="text" name="accountBranchName">
        <label for="Account Number">Account Number:</label>
        <input type="text" name="accountNumber">
        <label for="accountType">Account Type:</label>
        <select name="accountType" id="accountType">
            <option value="CHECKING">Checking</option>
            <option value="SAVINGS">Saving</option>
            <option value="CREDIT">Credit</option>
        </select>
        <label for="balance">Balance:</label>
        <input type="number" name="balance">
        <label for="accountStatus">Status:</label>
        <select name="accountStatus" id="accountStatus">
            <option value="ACTIVE">Active</option>
            <option value="INACTIVE">Inactive</option>
            <option value="CLOSED">Closed</option>
        </select>
        <label for="accountCreationDate">Creation Date:</label>
        <input type="date" name="accountCreationDate">
        <textarea name="incomeNotes" placeholder="Notes"></textarea>
        <button type="submit" id="submitBtn">Submit</button>
    </form>

</body>
</html>
