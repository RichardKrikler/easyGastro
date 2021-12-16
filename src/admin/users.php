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
                <option disabled hidden value="" selected>Typ</option>
                $typeOptions
            </select>
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
                <button type="button" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" data-bs-toggle="modal" data-bs-target="#deleteUserModal{$user['pk_user_id']}">
                    <span class="icon material-icons-outlined">close</span>
                </button>
            </div>
        </td>
    </tr>
</form>

<!-- Delete User - Modal -->
<div class="modal fade" id="deleteUserModal{$user['pk_user_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Benutzer {$user['name']} löschen</h3>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <form method="post" action="deleteUser.php" class="d-none">
                    <input type="hidden" value="{$user['pk_user_id']}" name="userId">
                    <button type="submit" class="btn bg-red text-white fs-5">Löschen</button>
                </form>
                <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
            </div>
        </div>
    </div>
</div>

TR;
}

$body = <<<BODY
<div class="col col-10 mx-auto">

<div class="d-flex justify-content-center">
    <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#createUser">Benutzer erstellen</button>
</div>

<form method="post" action="createUser.php">
    <div class="modal fade" id="createUser" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header d-flex justify-content-center border-bottom-0">
                        <h3 class="modal-title">Benutzer erstellen</h3>
                    </div>
                    <div class="modal-body">
                        <label for="createUserName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="createUserName" name="name" required>
                        
                        <label for="createUserPassword" class="form-label mt-3">Passwort</label>
                        <input type="password" class="form-control" id="createUserPassword" name="password" required>
                        
                        <label for="createUserType" class="form-label mt-3">Typ</label>
                        <select class="form-select" id="createUserType" aria-label="Type Selector" name="type" required>
                            <option disabled hidden selected value="">Typ</option>
                            <option value="Kellner">Kellner</option>
                            <option value="Küchenmitarbeiter">Küchenmitarbeiter</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="modal-footer d-flex justify-content-between border-top-0 mt-3">
                            <button type="submit" class="btn btn-primary text-white fs-5">Erstellen</button>
                        <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
                    </div>
                </div>
        </div>
    </div>
</form>

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
