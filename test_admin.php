<?php

include 'config/koneksi.php';
include 'classes/admin.php';

$admin = new Admin($conn);

$admin->setUsername('admin');

echo "Username: " . $admin->login();

?>