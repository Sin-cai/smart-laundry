<?php

include '../../middleware/admin.php';
include '../../config/koneksi.php';
include '../../classes/transaction.php';
include '../../classes/service.php';

$transaction = new Transaction($conn);
$service = new Service($conn);

$id = $_GET['id'];

$data = $transaction->getTransactionById($id);

$services = $service->getAllServices();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Transaction</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-sm">

        <div class="card-body">

            <h2 class="mb-4">
                Edit Transaction
            </h2>

            <form action="../../process/edit_transaction_process.php"
                  method="POST">

                <input type="hidden"
                       name="id"
                       value="<?= $data['id']; ?>">

                <div class="mb-3">

                    <label class="form-label">
                        Service
                    </label>

                    <select name="service_id"
                            class="form-select">

                        <?php
                        while($s = mysqli_fetch_assoc($services)) {
                        ?>

                            <option value="<?= $s['id']; ?>"

                                <?php
                                if($s['id'] == $data['service_id']) {
                                    echo "selected";
                                }
                                ?>

                            >

                                <?= $s['service_name']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Weight
                    </label>

                    <input type="number"
                           step="0.1"
                           name="weight"
                           class="form-control"
                           value="<?= $data['weight']; ?>">

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Update Transaction

                </button>

                <a href="transactions.php"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>