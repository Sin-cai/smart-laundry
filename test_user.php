<?php

include 'config/koneksi.php';
include 'classes/user.php';

$user = new User($conn);

$user->setUsername('admin');
$user->setPassword('123');

$login = $user->login();

if ($login > 0) {
    echo "Login berhasil!";
} else {
    echo "Login gagal!";
}

?>