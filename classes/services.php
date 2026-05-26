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

    public function getServiceName() {
        return $this->serviceName;
    }


    public function getPricePerKg() {
        return $this->pricePerKg;
    }

    
    public function getEstimatedHours() {
        return $this->estimatedHours;
    }

}

?>