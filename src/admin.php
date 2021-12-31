<?php

namespace easyGastro\admin;

use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';
require_once 'admin/AdminHeader.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Admin', $row);


$header = AdminHeader::getHeader() . AdminHeader::getNavigation(basename(__FILE__));

$body = <<<BODY
<div class="text-center pt-3">
    <h1 class="mt-5">Herzlich Willkommen auf der Adminseite von easyGastro!</h1>
    <h2 class="mt-5">Hier können Sie Daten hinzufügen, bearbeiten und löschen.</h2>
    <img src="resources/EGS_Logo_outlined_black_v1.png" style="width: 400px" class="mt-5">
</div>
BODY;

print(SiteTemplate::render('Admin - EGS', $header, $body));
