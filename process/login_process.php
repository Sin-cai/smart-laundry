<?php


session_start();
include '../config/koneksi.php';
include '../classes/User.php';

$user = new User($conn); // pastikan $conn ada di koneksi.php

$username = $_POST['username'];
$password = $_POST['password'];


$data = $user->login($username, $password);

// DEBUG SEMENTARA — hapus setelah beres


if($data) {
    $_SESSION['user_id']  = $data['id'];

    $_SESSION['username'] = $data['username'];

    $_SESSION['role']     = $data['role'];

    if($data['role'] == 'admin') {
        header("Location: ../pages/admin/index.php");
        exit; // ← WAJIB ADA
    } else {
        header("Location: ../pages/customer/index.php");
        exit; // ← WAJIB ADA
    }
} else {
    // Kalau login gagal, redirect balik dengan pesan
    header("Location: ../index.php?error=1");
    exit;
}