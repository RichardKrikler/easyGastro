<?php

header('Location: /admin/quantities.php');

use easyGastro\DB_User;

require_once 'DB_Admin_DrinkQuantities.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['drinkId']) || !isset($_POST['quantityId']) || !isset($_POST['price'])) {
    return;
}

DB_Admin_DrinkQuantities::createDrinkQuantity($_POST['drinkId'], $_POST['quantityId'], $_POST['price']);
