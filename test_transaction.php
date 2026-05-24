<?php

include 'classes/services.php';
include 'classes/transaction.php';

$service = new LaundryService(
    "Express",
    12000,
    6
);

$transaction = new Transaction(
    "Budi",
    $service,
    5
);

$total = $transaction->calculatePrice();

echo "Customer: " . $transaction->getCustomerName();
echo "<br>";

echo "Total Harga: Rp " . $total;
echo "<br>";

echo "Status: " . $transaction->getStatus();

?>  