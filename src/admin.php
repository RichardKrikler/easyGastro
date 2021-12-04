<?php

namespace easyGastro\admin;

use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';
require_once 'admin/AdminNav.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Admin', $row);


$adminNav = AdminNav::getNavigation('');

$header = <<<HEADER
<div class="header d-flex justify-content-between">
    <p class="invisible"></p>
    <h1 class="fw-normal py-3 fs-3 mb-0"><a href="/admin.php" class="text-white text-decoration-none">Adminseite</a></h1>
    <form method="post" class="d-flex flex-column justify-content-center my-auto">
        <button type="submit" name="logout" id="logoutBt" class="shadow-none bg-unset d-flex flex-column justify-content-center">
            <span class="icon material-icons-outlined mx-2 px-2 text-white">logout</span>
        </button>
    </form>
</div>

{$adminNav}

<hr class="mt-0">
HEADER;

$body = <<<BODY
BODY;

print(SiteTemplate::render('Admin - EGS', $header, $body));
