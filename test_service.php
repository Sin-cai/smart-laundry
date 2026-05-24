<?php

include 'classes/services.php';

$service = new LaundryService(
    "Express",
    12000,
    6
);

echo "Layanan: " . $service->getServiceName();
echo "<br>";

echo "Harga per KG: " . $service->getPricePerKg();
echo "<br>";

echo "Estimasi selesai: " . $service->getEstimatedHours() . " jam";

?>