<?php

header('Location: /admin/quantities.php');

use easyGastro\DB_User;

require_once 'DB_Admin_DrinkQuantities.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['drinkQuantityId']) || !isset($_POST['price']) || !isset($_POST['drinkId']) || !isset($_POST['quantityId'])) {
    return;
}

DB_Admin_DrinkQuantities::updateDrinkQuantityPrice($_POST['drinkQuantityId'], $_POST['price']);
DB_Admin_DrinkQuantities::updateDrinkQuantityDrink($_POST['drinkQuantityId'], $_POST['drinkId']);
DB_Admin_DrinkQuantities::updateDrinkQuantityQuantity($_POST['drinkQuantityId'], $_POST['quantityId']);
