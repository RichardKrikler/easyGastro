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
Pages::checkPage('Admin', $row);

if ((isset($row) && $row['typ'] !== 'Admin') || !isset($_POST['name']) || !isset($_POST['password']) || !isset($_POST['type'])) {
    return;
}

DB_Admin_Users::createUser($_POST['name'], password_hash($_POST['password'], PASSWORD_BCRYPT), $_POST['type']);

