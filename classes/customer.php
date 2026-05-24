<?php

include_once 'user.php';

class Customer extends User {

    protected $points = 0;

    // Method lihat status
    public function lihatStatus() {
        echo "Customer melihat status laundry";
    }

    // Getter points
    public function getPoints() {
        return $this->points;
    }

    // Setter points
    public function setPoints($points) {
        $this->points = $points;
    }

}

?>