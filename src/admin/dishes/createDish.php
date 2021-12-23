<?php

header('Location: /admin/dishes.php');

use easyGastro\DB_User;

require_once 'DB_Admin_Dishes.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['name']) || !isset($_POST['price']) || !isset($_POST['dishGroupId'])) {
    return;
}

DB_Admin_Dishes::createDish($_POST['name'], $_POST['price'], $_POST['dishGroupId']);
