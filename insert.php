<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add a Transaction</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Inline CSS to ensure the elements are hidden as soon as the HTML is parsed */
        .fields, #submitBtn {
            display: none;
        }
    </style>
</head>
<body>
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
                <option value="Saving">Saving Account</option>
                <option value="Credit">Credit Card</option>
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
                <option value="Saving">Saving Account</option>
                <option value="Checking">Checking Account</option>
                <option value="Credit">Credit Account</option>
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
                <option value="Saving">Saving Account</option>
                <option value="Checking">Checking Account</option>
                <option value="Credit">Credit Account</option>
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
            <input type="text" name="transactionSourceAccount" placeholder="Source Account">
            <input type="text" name="transactionDestinationAccount" placeholder="Destination Account">
            <input type="text" name="transactionType" placeholder="Transaction Type">
            <input type="number" name="transactionAmount" placeholder="Amount">
            <input type="date" name="transactionDate">
            <textarea name="transactionNotes" placeholder="Notes"></textarea>
        </div>
        <button type="submit" id="submitBtn">Submit</button>
    </form>

    <script src="script.js"></script>
</body>
</html>
