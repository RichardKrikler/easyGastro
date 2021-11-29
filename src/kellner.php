<?php

namespace easyGastro;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Kellner', $row);


$nav = <<<NAV
<div class="header d-flex justify-content-between">
    <p class="invisible"></p>
    <h1 class="text-white fw-normal py-3 fs-3 mb-0">Kellnerseite</h1>
    <form method="post">
        <button type="submit" name="logout" style="background-color: #6A6A6A" class="shadow-none mx-1 px-1 my-3">
            <span class="material-icons-outlined" style="color: white">logout</span>
        </button>
    </form>
</div>
NAV;

$body = <<<BODY
<br>
<button id="push-subscription-button" class="btn btn-primary">Push notifications!</button>
<script defer>const userId = "{$_SESSION['user']['id']}"</script>
BODY;

print(SiteTemplate::render('Kellner - EGS', $nav, $body));
