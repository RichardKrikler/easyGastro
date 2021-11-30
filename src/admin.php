<?php

namespace easyGastro;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Admin', $row);


$nav = <<<NAV
<div class="header d-flex justify-content-between">
    <p class="invisible"></p>
    <h1 class="text-white fw-normal py-3 fs-3 mb-0">Adminseite</h1>
    <form method="post" class="d-flex flex-column justify-content-center">
        <button type="submit" name="logout" id="logoutBt" class="shadow-none bg-unset">
            <span class="icon material-icons-outlined mx-2 px-2 text-white">logout</span>
        </button>
    </form>
</div>
NAV;

$body = <<<BODY

BODY;

print(SiteTemplate::render('Admin - EGS', $nav, $body));
