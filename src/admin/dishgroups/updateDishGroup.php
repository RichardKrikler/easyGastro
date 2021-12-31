<?php

header('Location: /admin/dishgroups.php');

use easyGastro\DB_User;

require_once 'DB_Admin_DishGroups.php';
require_once __DIR__ . '/../../DB_User.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['dishGroupId']) || !isset($_POST['name'])) {
    return;
}

DB_Admin_DishGroups::updateDishGroup($_POST['dishGroupId'], $_POST['name']);
