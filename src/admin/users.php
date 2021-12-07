<?php

namespace easyGastro\admin;

use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once '../SiteTemplate.php';
require_once '../Pages.php';
require_once '../db.php';
require_once '../DB_User.php';
require_once 'DB_Admin_Users.php';
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

<hr class="mt-0">
HEADER;

$tableRows = '';
foreach (DB_Admin_Users::getUsers() as $user) {
    $typeOptions = '';
    foreach (['Kellner', 'Küchenmitarbeiter', 'Admin'] as $type) {
        $selected = $user['typ'] == $type ? 'selected' : '';
        $typeOptions .= "<option value='$type' $selected>$type</option>";
    }

    $tableRows .= <<<TR
<form method="post" action="updateUser.php">
    <tr class="admin-user-row">
        <th scope="row" class="fw-normal text-center">
            {$user['pk_user_id']}
            <input type="hidden" value="{$user['pk_user_id']}" name="userId">
        </th>
        
        <td class="col-3">
            <input type="text" id="nameInput" class="form-control d-inline-block text-center" value="{$user['name']}" start_value="{$user['name']}" name="name">
        </td>
        
        <td class="col-3">
            <input type="password" id="passwortInput" class="form-control d-inline-block text-center" placeholder="‧‧‧" name="password" start_value="">
        </td>
        
        <td class="col-3">
            <select class="form-select" id="typeSelect" aria-label="Type Selector" name="type" start_value="{$user['typ']}">
                <option>Typ</option>
                $typeOptions
            </select>
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
                <span class="icon material-icons-outlined">close</span>
            </div>
        </td>
    </tr>
</form>
TR;
}

$body = <<<BODY
<div class="col col-10 mx-auto">

<div class="d-flex justify-content-center">
    <button class="btn btn-secondary mt-3 mb-5">Benutzer hinzufügen</button>
</div>

<table class="table mx-2">
    <thead>
        <tr>
            <th scope="col" class="fw-normal fs-5 text-center">ID</th>
            <th scope="col" class="fw-normal fs-5 text-center">Name</th>
            <th scope="col" class="fw-normal fs-5 text-center">Passwort</th>
            <th scope="col" class="fw-normal fs-5 text-center">Typ</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        $tableRows
    </tbody>
</table>
</div>
BODY;

print(SiteTemplate::render('Benutzer - Admin - EGS', $header, $body));
