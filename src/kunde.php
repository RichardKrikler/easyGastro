<?php

namespace easyGastro;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';
require_once 'customer/DB_Customer.php';

use easyGastro\Customer;

session_start();

$nav = <<<NAV
<div class="header text-center d-flex justify-content-between">
    <span id="payIcon" class="icon pay-icon material-icons-outlined d-flex flex-column justify-content-center mx-2 px-2 text-white" data-bs-toggle="modal" data-bs-target="#payModal"></span>
    <h1 id="startHeader" class="text-white fw-normal py-3 fs-3 mb-0">Startseite</h1>
    <h1 id="drinkHeader" class="text-white fw-normal py-3 fs-6 mb-0" style="display: none">Auswahl - Getränke</h1>
    <h1 id="foodHeader" class="text-white fw-normal py-3 fs-6 mb-0" style="display: none">Auswahl - Speisen</h1>
    <span id="orderIcon" class="icon order-icon material-icons-outlined d-flex flex-column justify-content-center mx-2 px-2 text-white" data-bs-toggle="modal" data-bs-target="#orderModal"></span>
</div>
NAV;

$drinkGroups = Customer\DB_Customer::getDrinkGroups();
$allDrinks = [];

foreach ($drinkGroups as $eachDrinkGroup) {
    $drinksFromGroup = Customer\DB_Customer::getDrinks($eachDrinkGroup['bezeichnung']);
    foreach ($drinksFromGroup as $eachDrink) {
        $drinksFromGroupAsString[] = $eachDrink['bezeichnung'];
    }
    $allDrinks[$eachDrinkGroup['bezeichnung']] = $drinksFromGroupAsString;
    $drinksFromGroupAsString = array();
}

$drinks = '<ul class="list-group list-group-flush mx-sm-3">';
foreach ($allDrinks as $eachDrinkGroup) {
    $drinks .= '<li class="list-group-item border-bottom-0"><p class="fw-bold mb-0">'
        . array_search($eachDrinkGroup, $allDrinks) . '</p><ul class="list-group list-group-flush">';
    foreach ($eachDrinkGroup as $eachDrink) {
        $drinks .= '<li class="list-group-item d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#drinkModal">
                    <p class="mb-0 d-flex flex-column justify-content-center">' . $eachDrink
                    . '</p><span class="icon material-icons-outlined">keyboard_arrow_right</span></li>';
    }
    $drinks .= '</ul></li>';
}
$drinks .= '</ul>';

$foodGroups = Customer\DB_Customer::getFoodGroups();
$allFood = [];

foreach ($foodGroups as $eachFoodGroup) {
    $foodFromGroup = Customer\DB_Customer::getFood($eachFoodGroup['bezeichnung']);
    foreach ($foodFromGroup as $eachFood) {
        $foodFromGroupAsString[] = $eachFood['bezeichnung'];
    }
    $allFood[$eachFoodGroup['bezeichnung']] = $foodFromGroupAsString;
    $foodFromGroupAsString = array();
}

$food = '<ul class="list-group list-group-flush mx-sm-3">';
foreach ($allFood as $eachFoodGroup) {
    $food .= '<li class="list-group-item border-bottom-0"><p class="fw-bold mb-0">'
        . array_search($eachFoodGroup, $allFood) . '</p><ul class="list-group list-group-flush">';
    foreach ($eachFoodGroup as $eachFood) {
        $food .= '<li class="list-group-item d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#foodModal">
                    <p class="mb-0 d-flex flex-column justify-content-center">' . $eachFood
            . '</p><span class="icon material-icons-outlined">keyboard_arrow_right</span></li>';
    }
    $food .= '</ul></li>';
}
$food .= '</ul>';

$drinkModal = <<<DRINKMODAL
<div class="modal fade" id="drinkModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Trinken</h3>
            </div>
            <div class="modal-body text-center d-flex justify-content-around">
                <div class="w-25">
                    <label for="drinkAmount" class="form-label">Menge:</label>
                    <select class="form-select" id="" aria-label="Default select example">
                        <option value="1" selected class="text-center">0,25</option>
                    </select>
                </div>
                <div class="w-25">
                    <label for="drinkCount" class="form-label">Anzahl:</label>
                    <input type="number" id="drinkCount" class="form-control">
                </div>
                <div>
                    <label for="drinkPrice" class="form-label">Preis:</label>
                    <div id="drinkPrice">100€</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <button type="button" class="btn bg-red fs-5" data-bs-dismiss="modal">Zurück</button>
                <button type="button" class="btn bg-green fs-5">Bestätigen</button>
            </div>
        </div>
    </div>
</div>
DRINKMODAL;

$foodModal = <<<FOODMODAL
<div class="modal fade" id="foodModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Essen</h3>
            </div>
            <div class="modal-body text-center d-flex justify-content-around">
                <div class="w-25">
                    <label for="foodCount" class="form-label">Anzahl:</label>
                    <input type="number" id="foodCount" class="form-control">
                </div>
                <div>
                    <label for="foodPrice" class="form-label">Preis:</label>
                    <div id="foodPrice">100€</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <button type="button" class="btn bg-red fs-5" data-bs-dismiss="modal">Zurück</button>
                <button type="button" class="btn bg-green fs-5">Bestätigen</button>
            </div>
        </div>
    </div>
</div>
FOODMODAL;

$payModal = <<<PAYMODAL
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Bezahlen</h3>
            </div>
            <div class="modal-body text-center">
                Um bezahlen zu können, müssen Sie einen Kellner rufen.
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <button type="button" class="btn bg-red fs-5" data-bs-dismiss="modal">Zurück</button>
                <button type="button" class="btn bg-green fs-5">Kellner rufen</button>
            </div>
        </div>
    </div>
</div>
PAYMODAL;

$orderModal = <<<ORDERMODAL
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Übersicht - Bestellung</h3>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#foodModal">
                        <p class="mb-0 w-50">blalala</p>
                        <div class="d-flex justify-content-between w-50">
                            <p class="mb-0">0,25l</p>
                            <p class="mb-0">2,50€</p>
                            <span class="icon close-icon material-icons-outlined c-red">close</span>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#foodModal">
                        <p class="mb-0 w-50">blalala</p>
                        <div class="d-flex justify-content-between w-50">
                            <p class="mb-0">0,25l</p>
                            <p class="mb-0">2,50€</p>
                            <span class="icon close-icon material-icons-outlined c-red">close</span>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between fw-bold" style="border-top: solid 2px black" data-bs-toggle="modal" data-bs-target="#foodModal">
                        <p class="mb-0 ">Gesamt</p>
                        <p class="mb-0">5000€</p>
                    </li>
                </ul>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <button type="button" class="btn bg-red fs-5" data-bs-dismiss="modal">Zurück</button>
                <button type="button" class="btn bg-green fs-5">Abschicken</button>
            </div>
        </div>
    </div>
</div>
ORDERMODAL;


$body = <<<BODY
<script src="customer/customer.js" defer></script>
<div class="d-flex">
    <div id="drinksButton" class="w-50 text-center bg-yellow py-3 fs-5" onclick="switchCategory('drinks')">Getränke</div>
    <div id="foodButton" class="w-50 text-center bg-orange py-3 fs-5" onclick="switchCategory('food')">Speisen</div>
</div>

<div id="welcomeMessage" class="text-center my-5 mx-4">
    <p class="fs-2">Herzlich Willkommen bei uns im Restaurant!</p>
    <p>Das ist die Seite zur Auswahl Ihrer Bestellung.</p>
    <p>Einfach Ihre Getränke und Speisen auswählen und die Bestellung abschicken.</p>
    <img src="resources/EGS_Logo_outlined_black_v1.png" alt="Logo" style="width: 80%">
</div>

<div id="drinks" style="display: none">
    $drinks
</div>

<div id="food" style="display: none">
    $food
</div>

$drinkModal
$foodModal
$payModal
$orderModal

BODY;

print(SiteTemplate::render('Kunde - EGS', $nav, $body));
