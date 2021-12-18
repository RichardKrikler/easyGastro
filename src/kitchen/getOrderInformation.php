<?php
header('content-type:application/json');

require_once "DB_Order.php";
use easyGastro\kitchen\DB_Order;
session_start();

$orders = DB_Order::getAllOrders();
echo json_encode($orders);
return $orders;