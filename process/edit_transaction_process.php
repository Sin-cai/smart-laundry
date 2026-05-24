<?php

include '../config/koneksi.php';
include '../classes/transaction.php';

$transaction = new Transaction($conn);

$id = $_POST['id'];
$service_id = $_POST['service_id'];
$weight = $_POST['weight'];

$result = $transaction->editTransaction(

    $id,
    $service_id,
    $weight

);

if($result) {

    header(
        "Location: ../pages/admin/transactions.php"
    );

} else {

    echo "Gagal edit transaksi";

}

?>