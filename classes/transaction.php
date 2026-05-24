<?php

class Transaction {

    protected $conn;

    public function __construct($db) {

        $this->conn = $db;

    }


    // ==============================
    // GET ALL TRANSACTIONS
    // ==============================

    public function getAllTransactions() {

        $query = "

            SELECT 

                transactions.*,
                users.name AS customer_name,
                users.phone,
                services.service_name

            FROM transactions

            JOIN users
            ON transactions.user_id = users.id

            JOIN services
            ON transactions.service_id = services.id

            ORDER BY transactions.id DESC

        ";

        return mysqli_query($this->conn, $query);

    }


    // ==============================
    // ADD TRANSACTION
    // ==============================

    public function addTransaction(

        $user_id,
        $service_id,
        $weight,
        $payment_status

    ) {

        // ==============================
        // GET SERVICE
        // ==============================

        $serviceQuery = mysqli_query(

            $this->conn,

            "SELECT * FROM services
             WHERE id='$service_id'"

        );

        $service = mysqli_fetch_assoc($serviceQuery);


        // ==============================
        // CALCULATE PRICE
        // ==============================

        $total_price =
            $weight * $service['price_per_kg'];


        // ==============================
        // GET USER POINTS
        // ==============================

        $userQuery = mysqli_query(

            $this->conn,

            "SELECT * FROM users
             WHERE id='$user_id'"

        );

        $user = mysqli_fetch_assoc($userQuery);

        $points = $user['points'];


        // ==============================
        // MEMBERSHIP DISCOUNT
        // ==============================

        $discount = 0;

        $membership = 'Bronze';

        if($points >= 100) {

            $membership = 'Silver';

            $discount = 5;

        }

        if($points >= 300) {

            $membership = 'Gold';

            $discount = 10;

        }


        // ==============================
        // FINAL TOTAL
        // ==============================

        $discount_amount =
            ($total_price * $discount) / 100;

        $final_total =
            $total_price - $discount_amount;


        // ==============================
        // ESTIMATED FINISH
        // ==============================

        $estimated_finish = date(

            'Y-m-d H:i:s',

            strtotime(
                "+" . $service['estimated_hours'] . " hours"
            )

        );


        // ==============================
        // INSERT TRANSACTION
        // ==============================

        $query = "

            INSERT INTO transactions (

                user_id,
                service_id,
                weight,
                total_price,
                payment_method,
                payment_status,
                status,
                transaction_date,
                estimated_finish

            ) VALUES (

                '$user_id',
                '$service_id',
                '$weight',
                '$final_total',
                'Cash',
                '$payment_status',
                'Pending',
                NOW(),
                '$estimated_finish'

            )

        ";

        return mysqli_query(
            $this->conn,
            $query
        );

    }


    // ==============================
    // UPDATE STATUS
    // ==============================

    public function updateStatus($id, $status) {

        $query = "

            UPDATE transactions

            SET status='$status'

            WHERE id='$id'

        ";

        return mysqli_query($this->conn, $query);

    }


    // ==============================
    // GET TRANSACTION BY ID
    // ==============================

    public function getTransactionById($id) {

        $query = "

            SELECT *

            FROM transactions

            WHERE id='$id'

        ";

        $result = mysqli_query(
            $this->conn,
            $query
        );

        return mysqli_fetch_assoc($result);

    }


    // ==============================
    // EDIT TRANSACTION
    // ==============================

    public function editTransaction(

        $id,
        $service_id,
        $weight

    ) {

        // GET SERVICE
        $serviceQuery = mysqli_query(

            $this->conn,

            "SELECT * FROM services
             WHERE id='$service_id'"

        );

        $service = mysqli_fetch_assoc($serviceQuery);


        // RECALCULATE PRICE
        $total_price =
            $weight * $service['price_per_kg'];


        $query = "

            UPDATE transactions

            SET

                service_id='$service_id',
                weight='$weight',
                total_price='$total_price'

            WHERE id='$id'

        ";

        return mysqli_query(
            $this->conn,
            $query
        );

    }


    // ==============================
    // DELETE TRANSACTION
    // ==============================

    public function deleteTransaction($id) {

        $query = "

            DELETE FROM transactions

            WHERE id='$id'

        ";

        return mysqli_query(
            $this->conn,
            $query
        );

    }

}

?>