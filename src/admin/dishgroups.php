<?php

namespace easyGastro\admin;

use DB_Admin_DishGroups;
use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once '../SiteTemplate.php';
require_once '../Pages.php';
require_once '../db.php';
require_once '../DB_User.php';
require_once 'dishgroups/DB_Admin_DishGroups.php';
require_once 'AdminHeader.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Admin', $row);


$header = AdminHeader::getHeader() . AdminHeader::getNavigation(basename(__FILE__));

$dishGroupRows = '';
foreach (DB_Admin_DishGroups::getDishGroups() as $dishGroup) {
    $dishGroupRows .= <<<TR
<form method="post" action="dishgroups/updateDishGroup.php">
    <tr class="admin-row">
        <th scope="row" class="fw-normal text-center">
            {$dishGroup['pk_speisegrp_id']}
            <input type="hidden" value="{$dishGroup['pk_speisegrp_id']}" name="dishGroupId">
        </th>
        
        <td class="col-3">
            <input type="text" id="nameInput" class="form-control d-inline-block text-center" value="{$dishGroup['bezeichnung']}" start_value="{$dishGroup['bezeichnung']}" name="name">
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
                <button type="button" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" data-bs-toggle="modal" data-bs-target="#deleteDishGroupModal{$dishGroup['pk_speisegrp_id']}">
                    <span class="icon material-icons-outlined">close</span>
                </button>
            </div>
        </td>
    </tr>
</form>

<!-- Delete DishGroup - Modal -->
<div class="modal fade" id="deleteDishGroupModal{$dishGroup['pk_speisegrp_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Speisegruppe {$dishGroup['bezeichnung']} löschen</h3>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <form method="post" action="dishgroups/deleteDishGroup.php" class="d-none">
                    <input type="hidden" value="{$dishGroup['pk_speisegrp_id']}" name="dishGroupId">
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
<div class="col col-10 mx-auto pt-3">

    <div class="d-flex justify-content-center">
        <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#createDishGroup">Speisegruppe erstellen</button>
    </div>
    
    <form method="post" action="dishgroups/createDishGroup.php">
        <div class="modal fade" id="createDishGroup" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header d-flex justify-content-center border-bottom-0">
                            <h3 class="modal-title">Speisegruppe erstellen</h3>
                        </div>
                        <div class="modal-body">
                            <label for="createDishGroupName" class="form-label">Bezeichnung</label>
                            <input type="text" class="form-control" id="createDishGroupName" name="name" required>
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
            $dishGroupRows
        </tbody>
    </table>
</div>
BODY;

print(SiteTemplate::render('Speisegruppen - Admin - EGS', $header, $body));
