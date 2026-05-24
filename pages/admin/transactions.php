<?php

include '../../middleware/admin.php';
include '../../config/koneksi.php';


// ==============================
// SEARCH & FILTER
// ==============================

$search = $_GET['search'] ?? '';

$status = $_GET['status'] ?? '';

$start_date = $_GET['start_date'] ?? '';

$end_date = $_GET['end_date'] ?? '';


// ==============================
// PAGINATION
// ==============================

$limit = 10;

$page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;

$start = ($page - 1) * $limit;


// ==============================
// TOTAL DATA QUERY
// ==============================

$totalQueryText = "

    SELECT COUNT(*) as total

    FROM transactions

    JOIN users
    ON transactions.user_id = users.id

    WHERE

        MONTH(transactions.transaction_date)
        = MONTH(CURRENT_DATE())

        AND

        YEAR(transactions.transaction_date)
        = YEAR(CURRENT_DATE())

";


// Search customer
if($search != '') {

    $totalQueryText .= "

        AND users.name LIKE '%$search%'

    ";

}


// Filter status
if($status != '') {

    $totalQueryText .= "

        AND transactions.status='$status'

    ";

}


// Filter tanggal
if($start_date != '' && $end_date != '') {

    $totalQueryText .= "

        AND DATE(transactions.transaction_date)

        BETWEEN '$start_date'
        AND '$end_date'

    ";

}


$totalQuery = mysqli_query(
    $conn,
    $totalQueryText
);

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalData / $limit);


// ==============================
// MAIN QUERY
// ==============================

$queryText = "

    SELECT 

        transactions.*,
        users.name AS customer_name,
        services.service_name

    FROM transactions

    JOIN users
    ON transactions.user_id = users.id

    JOIN services
    ON transactions.service_id = services.id

    WHERE 

        MONTH(transactions.transaction_date)
        = MONTH(CURRENT_DATE())

        AND

        YEAR(transactions.transaction_date)
        = YEAR(CURRENT_DATE())

";


// Search customer
if($search != '') {

    $queryText .= "

        AND users.name LIKE '%$search%'

    ";

}


// Filter status
if($status != '') {

    $queryText .= "

        AND transactions.status='$status'

    ";

}


// Filter tanggal
if($start_date != '' && $end_date != '') {

    $queryText .= "

        AND DATE(transactions.transaction_date)

        BETWEEN '$start_date'
        AND '$end_date'

    ";

}


$queryText .= "

    ORDER BY transactions.id DESC

    LIMIT $start, $limit

";


$query = mysqli_query(
    $conn,
    $queryText
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Transactions</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between mb-4">

        <h2>Transactions</h2>

        <a href="index.php"
           class="btn btn-secondary">

            Back

        </a>

    </div>


    <!-- FILTER -->
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-2">

                    <!-- Search -->
                    <div class="col-md-3">

                        <input type="text"
                               name="search"

                               class="form-control"

                               placeholder="Search customer..."

                               value="<?= $search; ?>">

                    </div>


                    <!-- Status -->
                    <div class="col-md-2">

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Pending"
                                <?= ($status == 'Pending') ? 'selected' : ''; ?>>

                                Pending

                            </option>

                            <option value="Washing"
                                <?= ($status == 'Washing') ? 'selected' : ''; ?>>

                                Washing

                            </option>

                            <option value="Drying"
                                <?= ($status == 'Drying') ? 'selected' : ''; ?>>

                                Drying

                            </option>

                            <option value="Ironing"
                                <?= ($status == 'Ironing') ? 'selected' : ''; ?>>

                                Ironing

                            </option>

                            <option value="Ready"
                                <?= ($status == 'Ready') ? 'selected' : ''; ?>>

                                Ready

                            </option>

                            <option value="Picked Up"
                                <?= ($status == 'Picked Up') ? 'selected' : ''; ?>>

                                Picked Up

                            </option>

                        </select>

                    </div>


                    <!-- Start Date -->
                    <div class="col-md-2">

                        <input type="date"
                               name="start_date"

                               class="form-control"

                               value="<?= $start_date; ?>">

                    </div>


                    <!-- End Date -->
                    <div class="col-md-2">

                        <input type="date"
                               name="end_date"

                               class="form-control"

                               value="<?= $end_date; ?>">

                    </div>


                    <!-- Buttons -->
                    <div class="col-md-3">

                        <button type="submit"
                                class="btn btn-primary">

                            Filter

                        </button>

                        <a href="transactions.php"
                           class="btn btn-danger">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- TABLE -->
    <table class="table table-bordered table-hover bg-white shadow-sm">

        <thead class="table-dark">

            <tr>

                <th>No</th>
                <th>Customer</th>
                <th>Service</th>
                <th>Weight</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Estimated Finish</th>
                <th>Action</th>

            </tr>

        </thead>

        <tbody>

        <?php

        $no = $start + 1;

        while($data = mysqli_fetch_assoc($query)) {

        ?>

            <tr>

                <td><?= $no++; ?></td>

                <td><?= $data['customer_name']; ?></td>

                <td><?= $data['service_name']; ?></td>

                <td><?= $data['weight']; ?> KG</td>

                <td>
                    Rp <?= number_format($data['total_price']); ?>
                </td>

                <td>

                    <span class="badge bg-primary">

                        <?= $data['status']; ?>

                    </span>

                </td>

                <td><?= $data['payment_status']; ?></td>

                <td><?= $data['estimated_finish']; ?></td>
                

                <td>
                    

                    <a href="edit_transaction.php?id=<?= $data['id']; ?>"
                       class="btn btn-primary btn-sm">

                        Edit

                    </a>

                    <a href="update_status.php?id=<?= $data['id']; ?>"
                       class="btn btn-warning btn-sm">

                        Status

                    </a>



                    <?php if($data['payment_status'] == 'Unpaid') { ?>

                        <a href="../../process/update_payment.php?id=<?= $data['id']; ?>"
                        class="btn btn-danger btn-sm">

                            Mark as Paid

                        </a>

                    <?php } else { ?>

                        <span class="badge bg-success">

                            Paid

                        </span>

                    <?php } ?>




                    <a href="../../process/delete_transaction_process.php?id=<?= $data['id']; ?>"
                       class="btn btn-danger btn-sm"

                       onclick="return confirm('Yakin ingin menghapus transaksi ini?')">

                        Delete

                    </a>

                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table>


    <!-- PAGINATION -->
    <div class="d-flex justify-content-between align-items-center mt-4">

        <!-- Previous & Next -->
        <div>

            <?php if($page > 1) { ?>

                <a href="?page=<?= $page - 1; ?>&search=<?= $search; ?>&status=<?= $status; ?>&start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>"
                class="btn btn-secondary btn-sm">

                    Previous

                </a>

            <?php } ?>


            <?php if($page < $totalPages) { ?>

                <a href="?page=<?= $page + 1; ?>&search=<?= $search; ?>&status=<?= $status; ?>&start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>"
                class="btn btn-secondary btn-sm">

                    Next

                </a>

            <?php } ?>

        </div>


        <!-- Number Pages -->
        <div>

            <?php for($i = 1; $i <= $totalPages; $i++) { ?>

                <a href="?page=<?= $i; ?>&search=<?= $search; ?>&status=<?= $status; ?>&start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>"
                class="btn <?= ($i == $page) ? 'btn-primary' : 'btn-outline-primary'; ?> btn-sm">

                    <?= $i; ?>

                </a>

            <?php } ?>

        </div>


        <!-- Go To Page -->
        <div>

            <form method="GET"
                  class="d-flex">

                <input type="hidden"
                       name="search"
                       value="<?= $search; ?>">

                <input type="hidden"
                       name="status"
                       value="<?= $status; ?>">

                <input type="hidden"
                       name="start_date"
                       value="<?= $start_date; ?>">

                <input type="hidden"
                       name="end_date"
                       value="<?= $end_date; ?>">


                <input type="number"
                       name="page"

                       min="1"
                       max="<?= $totalPages; ?>"

                       class="form-control form-control-sm me-2"

                       placeholder="Page">

                <button type="submit"
                        class="btn btn-dark btn-sm">

                    Go

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>