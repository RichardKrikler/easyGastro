<?php

header('Location: /admin/users.php');

use easyGastro\admin\users\DB_Admin_Users;
use easyGastro\DB_User;
use easyGastro\Pages;

require_once 'DB_Admin_Users.php';
require_once __DIR__ . '/../../DB_User.php';
require_once __DIR__ . '/../../Pages.php';


session_start();

$row = DB_User::getDataOfUser();

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['userId'])) {
    return;
}

DB_Admin_Users::deleteUser($_POST['userId']);
