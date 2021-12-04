<?php

namespace easyGastro\admin;

use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Admin', $row);


$pages = ['users.php' => 'User', 'tablegroups.php' => 'Tischgruppen', 'tables.php' => 'Tische', 'drinkgroups.php' => 'Getränkegruppen', 'drinks.php' => 'Getränke', 'quantities.php' => 'Mengen', 'dishgroups.php' => 'Speisegruppen', 'dishes.php' => 'Speisen', 'qr-codes.php' => 'QR-Codes'];
$navbarUl = '<ul class="navbar-nav flex-wrap justify-content-center">';
foreach ($pages as $url => $pageTitle) {
    //$boldClass = $currentPage == $page ? 'fw-normal text-decoration-underline' : '';
    $url = 'admin/' . $url;
    $navbarUl .= <<<LI
  <li class="nav-item">
    <h4 class="fw-light"><a class="nav-link text-black" href="$url">$pageTitle</a></h4>
  </li>
LI;
}
$navbarUl .= '</ul>';


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

<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        {$navbarUl}
    </div>
  </div>
</nav>

<hr class="mt-0">
NAV;

$body = <<<BODY
BODY;

print(SiteTemplate::render('Admin - EGS', $nav, $body));
