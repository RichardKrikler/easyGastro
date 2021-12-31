<?php

header('Location: /admin/qr-codes.php');

use easyGastro\DB_User;

require_once __DIR__ . '/../tables/DB_Admin_Tables.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin')) {
    return;
}

foreach (DB_Admin_Tables::getTables() as $table) {
    DB_Admin_Tables::updateTableCode($table['pk_tischnr_id'], DB_Admin_Tables::generateTableCode());
}
