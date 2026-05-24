<?php

include '../config/koneksi.php';
include '../classes/Transaction.php';

$transaction = new Transaction($conn);


// ==============================
// GET INPUT
// ==============================

$user_id = $_POST['user_id'];

$service_id = $_POST['service_id'];

$weight = $_POST['weight'];

$payment_status = $_POST['payment_status'];


// ==============================
// ADD TRANSACTION
// ==============================

$result = $transaction->addTransaction(

    $user_id,
    $service_id,
    $weight,
    $payment_status

);


// ==============================
// RESULT
// ==============================

if($result) {

    header(

        "Location: ../pages/admin/transactions.php"

    );

    exit;

} else {

    echo "

    <script>

        alert('Failed to add transaction');

        window.history.back();

    </script>

    ";

}

?>