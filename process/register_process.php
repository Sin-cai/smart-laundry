<?php

include '../config/koneksi.php';
include '../classes/User.php';

$user = new User($conn);


// ==============================
// GET INPUT
// ==============================

$name = $_POST['name'];

$username = $_POST['username'];

$password = $_POST['password'];

$phone = $_POST['phone'];


// ==============================
// CHECK USERNAME
// ==============================

$check = mysqli_query($conn, "

    SELECT *

    FROM users

    WHERE username='$username'

");

if(mysqli_num_rows($check) > 0) {

    echo "

    <script>

        alert('Username already used!');

        window.location='../register.php';

    </script>

    ";

    exit;

}


// ==============================
// REGISTER
// ==============================

$result = $user->register(

    $name,
    $username,
    $password,
    $phone

);


// ==============================
// RESULT
// ==============================

if($result) {

    echo "

    <script>

        alert('Register success!');

        window.location='../index.php';

    </script>

    ";

} else {

    echo "

    <script>

        alert('Register failed!');

        window.location='../register.php';

    </script>

    ";

}

?>