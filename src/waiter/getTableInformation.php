<?php
header('content-type:application/json');

require_once "DB_Table.php";
use easyGastro\waiter\DB_Table;
session_start();

$tableGroup = DB_Table::getTableGroupOfWaiter($_SESSION['user']['name']);
$orders = DB_Table::getOrders($tableGroup[0]['bezeichnung']);
echo json_encode($orders);
return $orders;


