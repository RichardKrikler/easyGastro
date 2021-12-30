<?php

namespace easyGastro\admin;

use DB_Admin_TableGroups;
use DB_Admin_Tables;
use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once '../SiteTemplate.php';
require_once '../Pages.php';
require_once '../db.php';
require_once '../DB_User.php';
require_once 'tables/DB_Admin_Tables.php';
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

$adminNav

<hr class="my-0">
HEADER;

$tableRows = '';
$tableGroups = DB_Admin_TableGroups::getTableGroups();
foreach (DB_Admin_Tables::getTables() as $table) {
    $tableGroupOptions = '';
    foreach ($tableGroups as $tableGroup) {
        $selected = $table['fk_pk_tischgrp_id'] == $tableGroup['pk_tischgrp_id'] ? 'selected' : '';
        $tableGroupOptions .= "<option value='{$tableGroup['pk_tischgrp_id']}' $selected>{$tableGroup['bezeichnung']}</option>";
    }

    $tableRows .= <<<TR
<form method="post" action="tables/updateTable.php">
    <tr class="admin-row">
        <th scope="row" class="fw-normal text-center">
            {$table['pk_tischnr_id']}
            <input type="hidden" value="{$table['pk_tischnr_id']}" name="tableId">
        </th>
        
        <td class="col-3">
            <input type="text" id="nameInput" class="form-control d-inline-block text-center" value="{$table['tischcode']}" start_value="{$table['tischcode']}" name="tableCode">
        </td>
        
        <td class="col-3">
            <select class="form-select" id="typeSelect" aria-label="Table-Group Selector" name="tableGroupId" start_value="{$table['fk_pk_tischgrp_id']}">
                <option disabled hidden value="" selected>Tischgruppe</option>
                $tableGroupOptions
            </select>
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
                <button type="button" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" data-bs-toggle="modal" data-bs-target="#deleteTableModal{$table['pk_tischnr_id']}">
                    <span class="icon material-icons-outlined">close</span>
                </button>
            </div>
        </td>
    </tr>
</form>

<!-- Delete Table - Modal -->
<div class="modal fade" id="deleteTableModal{$table['pk_tischnr_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Tisch {$table['tischcode']} löschen</h3>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <form method="post" action="tables/deleteTable.php" class="d-none">
                    <input type="hidden" value="{$table['pk_tischnr_id']}" name="tableId">
                    <button type="submit" class="btn bg-red text-white fs-5">Löschen</button>
                </form>
                <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
            </div>
        </div>
    </div>
</div>

TR;
}


$tableGroupOptions = '';
foreach ($tableGroups as $tableGroup) {
    $tableGroupOptions .= "<option value='{$tableGroup['pk_tischgrp_id']}'>{$tableGroup['bezeichnung']}</option>";
}

$body = <<<BODY
<div class="col col-10 mx-auto pt-3">

    <div class="d-flex justify-content-center">
        <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#addTable">Tisch hinzufügen</button>
    </div>
    
    <form method="post" action="tables/createTable.php">
        <div class="modal fade" id="addTable" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header d-flex justify-content-center border-bottom-0">
                            <h3 class="modal-title">Tisch hinzufügen</h3>
                        </div>
                        <div class="modal-body">
                            <label for="createtableCode" class="form-label">Tischcode</label>
                            <input type="text" class="form-control" id="createtableCode" name="tableCode" required>
                            
                            <label for="createUserType" class="form-label mt-3">Tischgruppe</label>
                            <select class="form-select" id="createUserType" aria-label="TableGroup Selector" name="tableGroupId" required>
                                <option disabled hidden selected value="">Tischgruppe</option>
                                $tableGroupOptions
                            </select>
                        </div>
                        <div class="modal-footer d-flex justify-content-between border-top-0 mt-3">
                                <button type="submit" class="btn btn-primary text-white fs-5">Hinzufügen</button>
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
                <th scope="col" class="col-5 fw-normal fs-5 text-center">Tischcode</th>
                <th scope="col" class="col-5 fw-normal fs-5 text-center">Tischgruppe</th>
                <th scope="col" class="col-3"></th>
            </tr>
        </thead>
        <tbody>
            $tableRows
        </tbody>
    </table>
</div>
BODY;

print(SiteTemplate::render('Tische - Admin - EGS', $header, $body));
