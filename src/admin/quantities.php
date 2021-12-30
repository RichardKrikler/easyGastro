<?php

namespace easyGastro\admin;

use DB_Admin_DrinkQuantities;
use DB_Admin_Drinks;
use DB_Admin_Quantities;
use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once '../SiteTemplate.php';
require_once '../Pages.php';
require_once '../db.php';
require_once '../DB_User.php';
require_once 'quantities/DB_Admin_Quantities.php';
require_once 'quantities/DB_Admin_DrinkQuantities.php';
require_once 'drinks/DB_Admin_Drinks.php';
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

$quantityRows = '';
$quantities = DB_Admin_Quantities::getQuantities();
foreach ($quantities as $quantity) {
    $quantityRows .= <<<TR
<form method="post" action="quantities/updateQuantity.php">
    <tr class="admin-row">
        <th scope="row" class="fw-normal text-center">
            {$quantity['pk_menge_id']}
            <input type="hidden" value="{$quantity['pk_menge_id']}" name="quantityId">
        </th>
        
        <td class="col-3">
            <input type="number" id="valueInput" class="form-control d-inline-block text-center" value="{$quantity['wert']}" start_value="{$quantity['wert']}" name="value" step="0.01">
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
                <button type="button" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" data-bs-toggle="modal" data-bs-target="#deleteQuantityModal{$quantity['pk_menge_id']}">
                    <span class="icon material-icons-outlined">close</span>
                </button>
            </div>
        </td>
    </tr>
</form>

<!-- Delete Quantity - Modal -->
<div class="modal fade" id="deleteQuantityModal{$quantity['pk_menge_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Menge {$quantity['wert']} löschen</h3>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <form method="post" action="quantities/deleteQuantity.php" class="d-none">
                    <input type="hidden" value="{$quantity['pk_menge_id']}" name="quantityId">
                    <button type="submit" class="btn bg-red text-white fs-5">Löschen</button>
                </form>
                <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
            </div>
        </div>
    </div>
</div>
TR;
}


$drinkQuantityRows = '';
$drinks = DB_Admin_Drinks::getDrinks();
foreach (DB_Admin_DrinkQuantities::getDrinkQuantities() as $drinkQuantity) {
    $quantityOptions = '';
    foreach ($quantities as $quantity) {
        $selected = $drinkQuantity['fk_pk_menge_id'] == $quantity['pk_menge_id'] ? 'selected' : '';
        $quantityOptions .= "<option value='{$quantity['pk_menge_id']}' $selected>{$quantity['wert']}</option>";
    }

    $drinkOptions = '';
    foreach ($drinks as $drink) {
        $selected = $drinkQuantity['fk_pk_getraenk_id'] == $drink['pk_getraenk_id'] ? 'selected' : '';
        $drinkOptions .= "<option value='{$drink['pk_getraenk_id']}' $selected>{$drink['bezeichnung']}</option>";
    }

    $drinkQuantityRows .= <<<TR
<form method="post" action="quantities/updateDrinkQuantity.php">
    <tr class="admin-row">
        <input type="hidden" value="{$drinkQuantity['pk_getraenkmg_id']}" name="drinkQuantityId">

        <td class="col-3">
            <select class="form-select" id="drinkSelect" aria-label="Drink Selector" name="drinkId" start_value="{$drinkQuantity['fk_pk_getraenk_id']}">
                <option disabled hidden value="" selected>Getränk</option>
                $drinkOptions
            </select>
        </td>
        
        <td class="col-3">
            <select class="form-select" id="quantitySelect" aria-label="Quantity Selector" name="quantityId" start_value="{$drinkQuantity['fk_pk_menge_id']}">
                <option disabled hidden value="" selected>Menge</option>
                $quantityOptions
            </select>
        </td>

        <td class="col-3">
            <input type="text" id="priceInput" class="form-control d-inline-block text-center" value="{$drinkQuantity['preis']}" start_value="{$drink['preis']}" name="price">
        </td>
        
        <td style="width: min-content">
            <div class="d-flex justify-content-evenly">
                <button onchange="submit()" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" disabled>
                    <span class="icon cloud-icon material-icons-outlined text-gray">cloud_upload<div></div></span>
                </button>
                <button type="button" class="bg-unset shadow-none d-flex justify-content-center flex-column" style="color: unset" data-bs-toggle="modal" data-bs-target="#deleteDrinkQuantityModal{$drinkQuantity['pk_getraenkmg_id']}">
                    <span class="icon material-icons-outlined">close</span>
                </button>
            </div>
        </td>
    </tr>
</form>

<!-- Delete Drink-Quantity - Modal -->
<div class="modal fade" id="deleteDrinkQuantityModal{$drinkQuantity['pk_getraenkmg_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Mengen-Zuweisung {$drinkQuantity['fk_pk_getraenk_id']} - {$drinkQuantity['fk_pk_menge_id']} löschen</h3>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <form method="post" action="quantities/deleteDrinkQuantity.php" class="d-none">
                    <input type="hidden" value="{$drinkQuantity['pk_getraenkmg_id']}" name="drinkQuantityId">
                    <button type="submit" class="btn bg-red text-white fs-5">Löschen</button>
                </form>
                <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
            </div>
        </div>
    </div>
</div>
TR;
}


$quantityOptions = '';
foreach ($quantities as $quantity) {
    $quantityOptions .= "<option value='{$quantity['pk_menge_id']}'>{$quantity['wert']}</option>";
}

$drinkOptions = '';
foreach ($drinks as $drink) {
    $drinkOptions .= "<option value='{$drink['pk_getraenk_id']}'>{$drink['bezeichnung']}</option>";
}

$body = <<<BODY
<div class="col col-10 mx-auto">

    <div class="d-flex justify-content-center">
        <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#createQuantity">Menge erstellen</button>
    </div>
    
    <form method="post" action="quantities/createQuantity.php">
        <div class="modal fade" id="createQuantity" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header d-flex justify-content-center border-bottom-0">
                            <h3 class="modal-title">Menge erstellen</h3>
                        </div>
                        <div class="modal-body">
                            <label for="createQuantityValue" class="form-label">Wert</label>
                            <input type="number" class="form-control" id="createQuantityValue" name="value" required step="0.01">
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
                <th scope="col" class="col-5 fw-normal fs-5 text-center">Wert</th>
                <th scope="col" class="col-3"></th>
            </tr>
        </thead>
        <tbody>
            $quantityRows
        </tbody>
    </table>


    <br>
    <h4 class="text-center mt-5 mb-4">Mengen-Zuweisung</h4>
    
    <div class="d-flex justify-content-center">
        <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#createDrinkQuantity">Menge zuweisen</button>
    </div>
    
    <form method="post" action="quantities/createDrinkQuantity.php">
        <div class="modal fade" id="createDrinkQuantity" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header d-flex justify-content-center border-bottom-0">
                            <h3 class="modal-title">Menge zuweisen</h3>
                        </div>
                        <div class="modal-body">
                            <label for="createDrinkQuantityDrink" class="form-label">Getränk</label>
                            <select class="form-select" id="createDrinkQuantityDrink" aria-label="Drink Selector" name="drinkId" required>
                                <option disabled hidden selected value="">Getränk</option>
                                $drinkOptions
                            </select>
                            
                            <label for="createDrinkQuantityQuantity" class="form-label mt-3">Menge (L)</label>
                            <select class="form-select" id="createDrinkQuantityQuantity" aria-label="Quantity Selector" name="quantityId" required>
                                <option disabled hidden selected value="">Menge</option>
                                $quantityOptions
                            </select>
                        
                            <label for="createDrinkQuantityPrice" class="form-label mt-3">Preis</label>
                            <input type="number" class="form-control" id="createDrinkQuantityPrice" name="price" required step="0.01">
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
                <th scope="col" class="col-3 fw-normal fs-5 text-center">Getränk</th>
                <th scope="col" class="col-3 fw-normal fs-5 text-center">Wert</th>
                <th scope="col" class="col-3 fw-normal fs-5 text-center">Preis</th>
                <th scope="col" class="col-3"></th>
            </tr>
        </thead>
        <tbody>
            $drinkQuantityRows
        </tbody>
    </table>
</div>
BODY;

print(SiteTemplate::render('Mengen - Admin - EGS', $header, $body));
