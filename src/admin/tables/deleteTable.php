<?php

header('Location: /admin/tables.php');

use easyGastro\DB_User;

require_once 'DB_Admin_Tables.php';
require_once __DIR__ . '/../../DB_User.php';
require_once __DIR__ . '/../../Pages.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['tableId'])) {
    return;
}

DB_Admin_Tables::deleteTable($_POST['tableId']);
