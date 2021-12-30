<?php

header('Location: /admin/quantities.php');

use easyGastro\DB_User;

require_once 'DB_Admin_Quantities.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['value'])) {
    return;
}

DB_Admin_Quantities::createQuantity($_POST['value']);
