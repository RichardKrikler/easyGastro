<?php

header('Location: /admin/qr-codes.php');

use easyGastro\DB_User;

require_once __DIR__ . '/../tables/DB_Admin_Tables.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['tableId'])) {
    return;
}

DB_Admin_Tables::updateTableCode($_POST['tableId'], DB_Admin_Tables::generateTableCode());
