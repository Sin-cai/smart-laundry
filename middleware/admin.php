<?php

session_start();

if (!isset($_SESSION['username'])) {

    header("Location: /laundry/index.php");
    exit;

}

if ($_SESSION['role'] != 'admin') {

    header("Location: /laundry/index.php");
    exit;

}

?>