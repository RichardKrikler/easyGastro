<?php

header('Location: /admin/dishes.php');

use easyGastro\DB_User;

require_once 'DB_Admin_Dishes.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['dishId']) || !isset($_POST['name']) || !isset($_POST['price']) || !isset($_POST['dishGroupId'])) {
    return;
}

DB_Admin_Dishes::updateDishName($_POST['dishId'], $_POST['name']);
DB_Admin_Dishes::updateDishPrice($_POST['dishId'], $_POST['price']);
DB_Admin_Dishes::updateDishGroupId($_POST['dishId'], $_POST['dishGroupId']);
