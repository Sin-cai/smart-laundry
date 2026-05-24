<?php

include_once 'user.php';

class Admin extends User {

    // Method tambah transaksi
    public function tambahTransaksi() {
        echo "Admin menambahkan transaksi laundry";
    }

    // Method update status
    public function updateStatus() {
        echo "Admin mengupdate status laundry";
    }

}

?>