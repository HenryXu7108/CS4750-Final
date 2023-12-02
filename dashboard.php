<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="your name">
    <meta name="description" content="include some description about your page">

    <title>Welcome to CS4750 Financial Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />

</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed top" aria-label="Main Navigation Bar">
    <div>
        <a class="navbar-brand" href="dashboard.php" style="padding-left: 20px; text-align: center">Financial Tracker</a>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" >
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
                <a href="main.html" class="nav-link ">View Records</a>
            </li>

            <li class="nav-item">
                <a href="logout.php" class="nav-link ">Log Out</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <h1>Finance Tracker</h1>
    <h2>Welcome, <?php echo $_SESSION['name']. '!' ;?> </h2>
</div>
</body>
</html>





