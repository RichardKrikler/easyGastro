<?php

namespace easyGastro\admin;

use DB_Admin_TableGroups;
use easyGastro\admin\users\DB_Admin_Users;
use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once '../SiteTemplate.php';
require_once '../Pages.php';
require_once '../db.php';
require_once '../DB_User.php';
require_once 'tablegroups/DB_Admin_TableGroups.php';
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

$tableGroupRows = '';
foreach (DB_Admin_TableGroups::getTablegroups() as $tableGroup) {
    $tableGroupRows .= <<<TR
<form method="post" action="tablegroups/updateTableGroup.php">
    <tr class="admin-row">
        <th scope="row" class="fw-normal text-center">
            {$tableGroup['pk_tischgrp_id']}
            <input type="hidden" value="{$tableGroup['pk_tischgrp_id']}" name="tableGroupId">
        </th>
        
        <td class="col-3">
            <input type="text" id="nameInput" class="form-control d-inline-block text-center" value="{$tableGroup['bezeichnung']}" start_value="{$tableGroup['bezeichnung']}" name="name">
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
                <button type="button" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" data-bs-toggle="modal" data-bs-target="#deleteTableGroupModal{$tableGroup['pk_tischgrp_id']}">
                    <span class="icon material-icons-outlined">close</span>
                </button>
            </div>
        </td>
    </tr>
</form>

<!-- Delete Tablegroup - Modal -->
<div class="modal fade" id="deleteTableGroupModal{$tableGroup['pk_tischgrp_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Tischgruppe {$tableGroup['bezeichnung']} löschen</h3>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <form method="post" action="tablegroups/deleteTableGroup.php" class="d-none">
                    <input type="hidden" value="{$tableGroup['pk_tischgrp_id']}" name="tableGroupId">
                    <button type="submit" class="btn bg-red text-white fs-5">Löschen</button>
                </form>
                <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
            </div>
        </div>
    </div>
</div>

TR;
}


$waiterTableGroupRows = '';
foreach (DB_Admin_TableGroups::getWaiters() as $waiter) {
    $tableGroupOptions = '';
    foreach (DB_Admin_TableGroups::getTablegroups() as $tableGroup) {
        $selected = $waiter['fk_pk_tischgrp_id'] == $tableGroup['pk_tischgrp_id'] ? 'selected' : '';
        $tableGroupOptions .= "<option value='{$tableGroup['pk_tischgrp_id']}' $selected>{$tableGroup['bezeichnung']}</option>";
    }

    $waiterTableGroupRows .= <<<TR
<form method="post" action="tablegroups/updateWaiterTableGroup.php">
    <tr class="admin-row">
        <th scope="row" class="fw-normal text-center">
            {$waiter['pk_user_id']}
            <input type="hidden" value="{$waiter['pk_user_id']}" name="tableGroupId">
        </th>
        
        <td class="col-3 text-center">
            {$waiter['name']}
        </td>
        
        <td class="col-3">
            <select class="form-select" id="typeSelect" aria-label="Table Group Selector" name="tableGroup" start_value="{$waiter['fk_pk_tischgrp_id']}">
                <option disabled hidden value="" selected>Typ</option>
                $tableGroupOptions
            </select>
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
            </div>
        </td>
    </tr>
</form>
TR;
}


$body = <<<BODY
<div class="col col-6 mx-auto">

    <div class="d-flex justify-content-center">
        <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#createTableGroup">Tischgruppe erstellen</button>
    </div>
    
    <form method="post" action="tablegroups/createTableGroup.php">
        <div class="modal fade" id="createTableGroup" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header d-flex justify-content-center border-bottom-0">
                            <h3 class="modal-title">Tischgruppe erstellen</h3>
                        </div>
                        <div class="modal-body">
                            <label for="createTablegroupName" class="form-label">Bezeichnung</label>
                            <input type="text" class="form-control" id="createTablegroupName" name="name" required>
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
                <th scope="col" class="col-3 fw-normal fs-5 text-center">ID</th>
                <th scope="col" class="col-5 fw-normal fs-5 text-center">Bezeichnung</th>
                <th scope="col" class="col-3"></th>
            </tr>
        </thead>
        <tbody>
            $tableGroupRows
        </tbody>
    </table>

    <table class="table mx-2">
        <thead>
            <tr>
                <th scope="col" class="col-3 fw-normal fs-5 text-center">ID</th>
                <th scope="col" class="col-3 fw-normal fs-5 text-center">Name</th>
                <th scope="col" class="col-5 fw-normal fs-5 text-center">Tischgruppe</th>
                <th scope="col" class="col-3"></th>
            </tr>
        </thead>
        <tbody>
            $waiterTableGroupRows
        </tbody>
    </table>
</div>
BODY;

print(SiteTemplate::render('Tischgruppen - Admin - EGS', $header, $body));
