<?php

class Service {

    protected $conn;

    public function __construct($db) {

        $this->conn = $db;

    }

    // Ambil semua layanan
    public function getAllServices() {

        $query = "

            SELECT * FROM services

            ORDER BY id DESC

        ";

        return mysqli_query(
            $this->conn,
            $query
        );

    }

}