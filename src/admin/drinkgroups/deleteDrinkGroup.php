<?php

header('Location: /admin/drinkgroups.php');

use easyGastro\DB_User;

require_once 'DB_Admin_DrinkGroups.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['drinkGroupId'])) {
    return;
}

DB_Admin_DrinkGroups::deleteDrinkGroup($_POST['drinkGroupId']);
