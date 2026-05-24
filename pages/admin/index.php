<?php



include '../../middleware/admin.php';
include '../../config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
}


// Total transaksi
$totalTransaction = mysqli_query(
    $conn,
    "SELECT COUNT(*) as total FROM transactions"
);

$transactionData = mysqli_fetch_assoc($totalTransaction);


// Total customer
$totalCustomer = mysqli_query(
    $conn,
    "SELECT COUNT(*) as total 
     FROM users 
     WHERE role='customer'"
);

$customerData = mysqli_fetch_assoc($totalCustomer);


// Total pendapatan
$totalIncome = mysqli_query(
    $conn,
    "SELECT SUM(total_price) as total 
     FROM transactions"
);

$incomeData = mysqli_fetch_assoc($totalIncome);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <h1 class="mb-4">
        Admin Dashboard
    </h1>

    <p>
        Welcome,
        <b><?= $_SESSION['username']; ?></b>
    </p>

    <div class="row">

        <div class="col-md-4">

            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <h5>Total Transactions</h5>

                    <h2>
                        <?= $transactionData['total']; ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <h5>Total Customers</h5>

                    <h2>
                        <?= $customerData['total']; ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <h5>Total Income</h5>

                    <h2>
                        Rp <?= number_format($incomeData['total']); ?>
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <div class="mt-4">

        <a href="add_transaction.php"
           class="btn btn-primary">
            Add Transaction
        </a>

        <a href="transactions.php"
           class="btn btn-success">
            View Transactions
        </a>

        <a href="../../logout.php"
           class="btn btn-danger">
            Logout
        </a>

    </div>

</div>

</body>
</html>