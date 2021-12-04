<?php

namespace easyGastro\admin;

use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once '../SiteTemplate.php';
require_once '../Pages.php';
require_once '../db.php';
require_once '../DB_User.php';
require_once 'AdminNav.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Admin', $row);


$adminNav = AdminNav::getNavigation(basename(__FILE__));

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

<hr class="my-0">
HEADER;

$body = <<<BODY
<table class="table">
    <thead>
    <tr>
        <th scope="col" class="fw-normal fs-5 text-center">ID</th>
        <th scope="col" class="fw-normal fs-5 text-center">Name</th>
        <th scope="col" class="fw-normal fs-5 text-center">Passwort</th>
        <th scope="col" class="fw-normal fs-5 text-center">Typ</th>
        <th scope="col" class="fw-normal fs-5 text-center"></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th scope="row" class="fw-normal text-center">1</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
    </tr>
    <tr>
        <th scope="row" class="fw-normal text-center">2</th>
        <td>Jacob</td>
        <td>Thornton</td>
        <td>@fat</td>
    </tr>
    <tr>
        <th scope="row" class="fw-normal text-center">3</th>
        <td>Larry the Bird</td>
        <td>@twitter</td>
        <td class="col-4">
            <input type="text" id="textInput1" class="form-control d-inline-block text-center">
        </td>
        <td style="width: min-content">
            <span class="icon material-icons-outlined">cloud_upload</span>
            <span class="icon material-icons-outlined">close</span>
        </td>
    </tr>
    </tbody>
</table>
BODY;

print(SiteTemplate::render('Benutzer - Admin - EGS', $header, $body));
