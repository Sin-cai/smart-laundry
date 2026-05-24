<?php

include '../config/koneksi.php';

$id = $_GET['id'];


// ==============================
// UPDATE PAYMENT
// ==============================

$query = "

    UPDATE transactions

    SET payment_status='Paid'

    WHERE id='$id'

";

$result = mysqli_query($conn, $query);


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

        alert('Failed to update payment');

        window.history.back();

    </script>

    ";

}

?>