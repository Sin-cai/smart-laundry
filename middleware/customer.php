<?php

session_start();

if (!isset($_SESSION['username'])) {

    header("Location: /laundry/index.php");
    exit;

}

if ($_SESSION['role'] != 'customer') {

    header("Location: /laundry/index.php");
    exit;

}

?>