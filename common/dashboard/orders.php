<?php
function getOrders() {
    $file = fopen("data/orders.txt", "r");
    $orders = [];
    while (($line = fgets($file)) !== false)
        $orders[] = unserialize($line);
    fclose($file);
    return $orders;
}

