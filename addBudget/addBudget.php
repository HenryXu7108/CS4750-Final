<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add a Budget</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

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
                <a class="nav-link " aria-current="page" href="../dashboard.php">DashBoard</a>
            </li>

            <li class="nav-item">
                <a href="../insert.php" class="nav-link ">Insert Records</a>
            </li>

            <li class="nav-item">
                <a href="../addAccount/addAccount.php" class="nav-link">Add Account</a>
            </li>

            <li class="nav-item">
                <a href="../addBillNotification/addBillNotification.php" class="nav-link">Add BillNotification</a>
            </li>

            <li class="nav-item">
                <a href="addBudget/addBudget.php" class="nav-link">Add Budget</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    View Records
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../spending_income.php">Spending & Income</a></li>
                    <li><a class="dropdown-item" href="../budget_transac.php">Budget/Transaction/Account</a></li>
                    <li><a class="dropdown-item" href="../investment.php">Investment</a></li>
                    <li><a class="dropdown-item" href="../card.php">Cards</a></li>
                    <li><a class="dropdown-item" href="../billnoti.php">Bill Notification</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="logout.php" class="nav-link ">Log Out</a>
            </li>
        </ul>
    </div>
</nav>
    <h1>Add a Budget</h1>
    <form id="BudgetForm" action="submit_addBudget.php" method="post">
        <label for="budgetPurpose">Budget Purpose:</label>
        <input type="text" name="budgetPurpose">
        <label for="Amount">Amount:</label>
        <input type="number" name="Amount">
        <button type="submit" id="submitBtn">Submit</button>
    </form>

</body>
</html>
