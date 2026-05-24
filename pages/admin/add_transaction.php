<?php

include '../../middleware/admin.php';
include '../../config/koneksi.php';
include '../../classes/service.php';


// ==============================
// CHECK LOGIN
// ==============================

if (!isset($_SESSION['username'])) {

    header("Location: ../../index.php");

    exit;

}


// ==============================
// SERVICE OBJECT
// ==============================

$service = new Service($conn);

$services = $service->getAllServices();


// ==============================
// GET CUSTOMERS
// ==============================

$customers = mysqli_query(

    $conn,

    "SELECT * FROM users WHERE role='customer'"

);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
      rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
        

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <!-- TITLE -->
                    <h2 class="mb-4">

                        Add Transaction

                    </h2>


                    <!-- FORM -->
                    <form action="../../process/add_transaction_process.php"
                          method="POST">


                        <!-- CUSTOMER -->
                        <div class="mb-3">

                            <label class="form-label">

                                Customer

                            </label>

                            <select name="user_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    -- Select Customer --
                                </option>

                                <?php
                                while($customer = mysqli_fetch_assoc($customers)) {
                                ?>

                                    <option value="<?= $customer['id']; ?>">

                                        <?= $customer['name']; ?>

                                        -

                                        <?= $customer['phone']; ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                        <!-- SERVICE -->
                        <div class="mb-3">

                            <label class="form-label">

                                Service

                            </label>

                            <select name="service_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    -- Select Service --
                                </option>

                                <?php
                                while($data = mysqli_fetch_assoc($services)) {
                                ?>

                                    <option value="<?= $data['id']; ?>">

                                        <?= $data['service_name']; ?>

                                        -

                                        Rp <?= number_format($data['price_per_kg']); ?>/KG

                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                        <!-- WEIGHT -->
                        <div class="mb-3">

                            <label class="form-label">

                                Weight (KG)

                            </label>

                            <input type="number"
                                   step="0.1"
                                   name="weight"

                                   class="form-control"

                                   placeholder="Enter weight"

                                   required>

                        </div>


                        <!-- PAYMENT STATUS -->
                        <div class="mb-4">

                            <label class="form-label">

                                Payment Status

                            </label>

                            <select name="payment_status"
                                    class="form-select">

                                <option value="Unpaid">

                                    Unpaid

                                </option>

                                <option value="Paid">

                                    Paid

                                </option>

                            </select>

                        </div>


                        <!-- BUTTON -->
                        <button type="submit"
                                class="btn btn-primary">

                            Save Transaction

                        </button>

                        <a href="transactions.php"
                           class="btn btn-secondary">

                            Back

                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function() {

    $('select[name="user_id"]').select2({

        placeholder: "Search customer phone/name",

        width: '100%'

    });

});

</script>

</body>
</html>