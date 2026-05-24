<?php

include '../config/koneksi.php';
include '../classes/transaction.php';

$transaction = new Transaction($conn);

$id = $_GET['id'];

$result = $transaction->deleteTransaction($id);

if($result) {

    header(
        "Location: ../pages/admin/transactions.php"
    );

} else {

    echo "Gagal hapus transaksi";

}

?>