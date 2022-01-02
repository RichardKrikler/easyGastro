<?php

header('Location: /admin/tables.php');

use easyGastro\DB_User;

require_once 'DB_Admin_Tables.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['tableId']) || !isset($_POST['tableGroupId'])) {
    print(isset($_POST['tableCode']));
    return;
}

DB_Admin_Tables::updateTableGroupId($_POST['tableId'], $_POST['tableGroupId']);
