<?php

header('Location: /admin/tables.php');

use easyGastro\DB_User;

require_once 'DB_Admin_Tables.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['tableCode']) || !isset($_POST['tableGroupId'])) {
    return;
}

DB_Admin_Tables::createTable($_POST['tableCode'], $_POST['tableGroupId']);

