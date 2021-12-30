<?php

namespace easyGastro\admin;

use DB_Admin_Dishes;
use DB_Admin_DishGroups;
use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once '../SiteTemplate.php';
require_once '../Pages.php';
require_once '../db.php';
require_once '../DB_User.php';
require_once 'dishes/DB_Admin_Dishes.php';
require_once 'dishgroups/DB_Admin_DishGroups.php';
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

<hr class="mt-0">
HEADER;

$dishRows = '';
$dishGroups = DB_Admin_DishGroups::getDishGroups();
foreach (DB_Admin_Dishes::getDishes() as $dish) {
    $dishGroupOptions = '';
    foreach ($dishGroups as $dishGroup) {
        $selected = $dish['fk_pk_speisegrp_id'] == $dishGroup['pk_speisegrp_id'] ? 'selected' : '';
        $dishGroupOptions .= "<option value='{$dishGroup['pk_speisegrp_id']}' $selected>{$dishGroup['bezeichnung']}</option>";
    }

    $dishRows .= <<<TR
<form method="post" action="dishes/updateDish.php">
    <tr class="admin-row">
        <th scope="row" class="fw-normal text-center">
            {$dish['pk_speise_id']}
            <input type="hidden" value="{$dish['pk_speise_id']}" name="dishId">
        </th>
        
        <td class="col-3">
            <input type="text" id="nameInput" class="form-control d-inline-block text-center" value="{$dish['bezeichnung']}" start_value="{$dish['bezeichnung']}" name="name">
        </td>
        
        <td class="col-3">
            <input type="number" id="priceInput" class="form-control d-inline-block text-center" name="price" required step="0.01" value="{$dish['preis']}" start_value="{$dish['preis']}">
        </td>
        
        <td class="col-3">
            <select class="form-select" id="typeSelect" aria-label="Type Selector" name="dishGroupId" start_value="{$dish['fk_pk_speisegrp_id']}">
                <option disabled hidden value="" selected>Speisegruppe</option>
                $dishGroupOptions
            </select>
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
                <button type="button" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" data-bs-toggle="modal" data-bs-target="#deleteDishModal{$dish['pk_speise_id']}">
                    <span class="icon material-icons-outlined">close</span>
                </button>
            </div>
        </td>
    </tr>
</form>

<!-- Delete Dish - Modal -->
<div class="modal fade" id="deleteDishModal{$dish['pk_speise_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Speise {$dish['bezeichnung']} löschen</h3>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <form method="post" action="dishes/deleteDish.php" class="d-none">
                    <input type="hidden" value="{$dish['pk_speise_id']}" name="dishId">
                    <button type="submit" class="btn bg-red text-white fs-5">Löschen</button>
                </form>
                <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
            </div>
        </div>
    </div>
</div>

TR;
}


$dishGroupOptions = '';
foreach ($dishGroups as $dishGroup) {
    $dishGroupOptions .= "<option value='{$dishGroup['pk_speisegrp_id']}'>{$dishGroup['bezeichnung']}</option>";
}

$body = <<<BODY
<div class="col col-10 mx-auto">

    <div class="d-flex justify-content-center">
        <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#createDishGroup">Speise hinzufügen</button>
    </div>
    
    <form method="post" action="dishes/createDish.php">
        <div class="modal fade" id="createDishGroup" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header d-flex justify-content-center border-bottom-0">
                            <h3 class="modal-title">Speise hinzufügen</h3>
                        </div>
                        <div class="modal-body">
                            <label for="createDishName" class="form-label">Bezeichnung</label>
                            <input type="text" class="form-control" id="createDishName" name="name" required>
                            
                            <label for="priceInput" class="form-label mt-3">Preis:</label>
                            <input type="number" id="priceInput" class="form-control" name="price" required step="0.01">
                            
                            <label for="createDishGroup" class="form-label mt-3">Speisegruppe</label>
                            <select class="form-select" id="createDishGroup" aria-label="Dish-Group Selector" name="dishGroupId" required>
                                <option disabled hidden selected value="">Speisegruppe</option>
                                $dishGroupOptions
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
                <th scope="col" class="col-3 fw-normal fs-5 text-center">ID</th>
                <th scope="col" class="col-5 fw-normal fs-5 text-center">Bezeichnung</th>
                <th scope="col" class="col-1 fw-normal fs-5 text-center">Preis</th>
                <th scope="col" class="col-5 fw-normal fs-5 text-center">Speisegruppe</th>
                <th scope="col" class="col-3"></th>
            </tr>
        </thead>
        <tbody>
            $dishRows
        </tbody>
    </table>
</div>
BODY;

print(SiteTemplate::render('Speisen - Admin - EGS', $header, $body));
