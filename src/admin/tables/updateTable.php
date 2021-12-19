<?php

header('Location: /admin/tables.php');

use easyGastro\DB_User;
use easyGastro\Pages;

require_once 'DB_Admin_Tables.php';
require_once __DIR__ . '/../../DB_User.php';
require_once __DIR__ . '/../../Pages.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Admin', $row);

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['tableId']) || !isset($_POST['tableCode']) || !isset($_POST['tableGroupId'])) {
    return;
}

DB_Admin_Tables::updateTableCode($_POST['tableId'], $_POST['tableCode']);
DB_Admin_Tables::updateTableGroupId($_POST['tableId'], $_POST['tableGroupId']);
