<?php

class LaundryService {

    // Property
    protected $serviceName;
    protected $pricePerKg;
    protected $estimatedHours;

    // Constructor
    public function __construct($serviceName, $pricePerKg, $estimatedHours) {

        $this->serviceName = $serviceName;
        $this->pricePerKg = $pricePerKg;
    }

    // Getter service name
    public function getServiceName() {
        return $this->serviceName;
    }

    // Getter price
    public function getPricePerKg() {
        return $this->pricePerKg;
    }

    // Getter estimasi
    public function getEstimatedHours() {
        return $this->estimatedHours;
    }

}

?>