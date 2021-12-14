<?php
header('content-type:application/json');

require_once "DB_Table.php";
use easyGastro\waiter\DB_Table;
session_start();

$tableGroup = DB_Table::getTableGroupOfWaiter($_SESSION['user']['name']);
$tables = DB_Table::getTableIDs($tableGroup[0]['bezeichnung']);
echo json_encode($tables);
return $tables;


