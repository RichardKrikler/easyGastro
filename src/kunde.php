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
<div class="header text-center">
    <h1 class="text-white fw-normal py-3 fs-3 mb-0">Startseite</h1>
</div>
NAV;

$drinkGroups = Customer\DB_Customer::getDrinkGroups();
$allDrinks = [];

foreach ($drinkGroups as $eachGroup) {
    $drinksFromGroup = Customer\DB_Customer::getDrinks($eachGroup['bezeichnung']);
    foreach ($drinksFromGroup as $eachDrink) {
        $drinksFromGroupAsString[] = $eachDrink['bezeichnung'];
    }
    $allDrinks[$eachGroup['bezeichnung']] = $drinksFromGroupAsString;
    $drinksFromGroupAsString = array();
}

$drinks = '<ul class="list-group list-group-flush mx-sm-3">';
foreach ($allDrinks as $eachGroup) {
    $drinks .= '<li class="list-group-item border-bottom-0"><p class="fw-bold mb-0">'
        . array_search($eachGroup, $allDrinks) . '</p><ul class="list-group list-group-flush">';
    foreach ($eachGroup as $eachDrink) {
        $drinks .= '<li class="list-group-item d-flex justify-content-between">
                    <p class="mb-0 d-flex flex-column justify-content-center">' . $eachDrink
                    . '</p><span class="icon material-icons-outlined">keyboard_arrow_right</span></li>';
    }
    $drinks .= '</ul></li>';
}
$drinks .= '</ul>';


$food = 'FOOOOOOOOOOOOOOOOOOOOOD';

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
BODY;

print(SiteTemplate::render('Kunde - EGS', $nav, $body));
