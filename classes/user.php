<?php

class User {

    protected $conn;

    public function __construct($db) {

        $this->conn = $db;

    }

    // ==============================
    // LOGIN
    // ==============================

    public function login($username, $password) {

        $query = "

            SELECT *

            FROM users

            WHERE username='$username'

        ";

        $result = mysqli_query(
            $this->conn,
            $query
        );

        if(mysqli_num_rows($result) > 0) {

            $data = mysqli_fetch_assoc($result);

            // Verify password
            if(password_verify(
                $password,
                $data['password']
            )) {

                return $data;

            }

        }

        return false;

    }


    // ==============================
    // REGISTER
    // ==============================

    public function register(

        $name,
        $username,
        $password,
        $phone

    ) {

        // Encrypt password
        $password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $query = "

            INSERT INTO users(

                name,
                username,
                password,
                role,
                phone,
                points

            )

            VALUES(

                '$name',
                '$username',
                '$password',
                'customer',
                '$phone',
                0

            )

        ";

        return mysqli_query(
            $this->conn,
            $query
        );

    }

}

?>