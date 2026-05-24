<?php

include '../config/koneksi.php';
include '../classes/Transaction.php';

$transaction = new Transaction($conn);

$id = $_POST['id'];
$status = $_POST['status'];

$result = $transaction->updateStatus($id, $status);

if ($result) {

    // kalau status selesai / picked up
    if ($status == 'Picked Up') {

        // ambil data transaksi
        $trx = mysqli_query($conn, "
            SELECT *
            FROM transactions
            WHERE id = '$id'
        ");

        $data = mysqli_fetch_assoc($trx);

        if ($data) {

            $user_id = $data['user_id'];
            $total_price = $data['total_price'];

            // 1 point tiap Rp10.000
            $point = floor($total_price / 10000);

            // tambah point user
            mysqli_query($conn, "
                UPDATE users
                SET points = points + $point
                WHERE id = '$user_id'
            ");
        }
    }

    header("Location: ../pages/admin/transactions.php");
    exit();

} else {

    echo "Gagal update status";

}

?>