<?php

header('Location: /admin/drinks.php');

use easyGastro\DB_User;

require_once 'DB_Admin_Drinks.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['drinkId']) || !isset($_POST['name']) || !isset($_POST['drinkGroupId'])) {
    return;
}

DB_Admin_Drinks::updateDrinkName($_POST['drinkId'], $_POST['name']);
DB_Admin_Drinks::updateDrinkGroupId($_POST['drinkId'], $_POST['drinkGroupId']);
