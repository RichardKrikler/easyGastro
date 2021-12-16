<?php

namespace easyGastro;

use easyGastro\kitchen\DB_Order;
use easyGastro\waiter\DB_Table;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';
require_once 'kitchen/DB_Order.php';
require_once 'waiter/DB_Table.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Küchenmitarbeiter', $row);


$nav = <<<NAV
<div class="header d-flex justify-content-between">
    <p class="invisible"></p>
    <h1 class="text-white fw-normal py-3 fs-3 mb-0">Küchenseite</h1>
    <form method="post">
        <button type="submit" name="logout" id="logoutBt" style="background-color: #6A6A6A" class="shadow-none mx-1 px-1 my-3">
            <span class="material-icons-outlined" style="color: white">logout</span>
        </button>
    </form>
</div>
NAV;

$allOrdersOpen = DB_Order::getOrders("Offen");
$allOrdersInProgress = DB_Order::getOrders("In-Bearbeitung");
$allOrdersFinished = DB_Order::getOrders("Abholbereit");

$body = <<<BODY
<script src="kitchen/kitchen.js" defer></script>
<div class="mx-5 my-5">
    <h4><li>neue Bestellungen</li></h4>
BODY;

foreach ($allOrdersOpen as $eachOrderOpen){

    $foodOfOrderOpen = DB_Table::getFoodOfTable($eachOrderOpen['fk_pk_tischnr_id']);
    $drinksOfOrderOpen = DB_Table::getDrinksOfTable($eachOrderOpen['fk_pk_tischnr_id']);
    $tableGroupOfOrderOpen = DB_Table::getTableGroupOfTable($eachOrderOpen['fk_pk_tischnr_id']);

    $body .= <<<BODY
<button class="table-kitchen bg-red d-inline-block m-2" id="order_{$eachOrderOpen['pk_bestellung_id']}" 
data-bs-toggle="modal" data-bs-target="#modal{$eachOrderOpen['pk_bestellung_id']}">
    <p class="fs-5 mb-0 px-4 py-3">Bestellung - Tisch {$eachOrderOpen['fk_pk_tischnr_id']}</p>
</button>

<div class="modal fade" id="modal{$eachOrderOpen['fk_pk_tischnr_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Bestellung - Tisch {$eachOrderOpen['fk_pk_tischnr_id']}</h3>
            </div>
            <div class="row">
                <div class="col">
                    <p class="mx-4">Information:</p>
                    <ul class="mx-2">
BODY;
    foreach ($drinksOfOrderOpen as $drinkOfOrderOpen) {
        $body .= <<<BODY
                        <li>{$drinkOfOrderOpen['anzahl']} {$drinkOfOrderOpen['bezeichnung']} {$drinkOfOrderOpen['menge']}l</li>
BODY;
    }
    $body .= <<<BODY
                        <hr style="margin: 5px;">
BODY;

    foreach ($foodOfOrderOpen as $foodOfOO){
        $body .= <<<BODY
                        <li>{$foodOfOO['anzahl']} {$foodOfOO['bezeichnung']}</li>
BODY;
    }

    $body .= <<<BODY
                
                    </ul>
                </div>
                <div class="col">
                    <p>Tischgruppe: {$tableGroupOfOrderOpen[0]['bezeichnung']}</p>
                    <p>Status: </p>
                    <h2 class="text-center">{$eachOrderOpen['status']}</h2>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <button type="button" class="btn bg-secondary fs-5 text-white" data-bs-dismiss="modal">Zurück</button>
                <form method="get">
                    <button type="submit" class="btn bg-secondary fs-5 text-white" name="buttonInProgress"
                    value="{$eachOrderOpen['pk_bestellung_id']}">In-Bearbeitung setzen</button>
                </form>
            </div>
        </div>
    </div>
</div>
BODY;
}

if(isset($_GET['buttonInProgress'])){
    DB_Order::updateStatusOfOrder('In-Bearbeitung', $_GET['buttonInProgress']);
    header("Location: kueche.php");
}

$body .= <<<BODY
</div>
<div class="mx-5 my-5">
    <h4><li>Bestellungen in Arbeit</li></h4>
BODY;

foreach ($allOrdersInProgress as $eachOrderInProgress){

    $foodOfOrderInProgress = DB_Table::getFoodOfTable($eachOrderInProgress['fk_pk_tischnr_id']);
    $drinksOfOrderInProgress = DB_Table::getDrinksOfTable($eachOrderInProgress['fk_pk_tischnr_id']);
    $tableGroupOfOrderInProgress = DB_Table::getTableGroupOfTable($eachOrderInProgress['fk_pk_tischnr_id']);

    $body .= <<<BODY
<button class="table-kitchen bg-yellow d-inline-block m-2" id="order_{$eachOrderInProgress['pk_bestellung_id']}" 
data-bs-toggle="modal" data-bs-target="#modal{$eachOrderInProgress['pk_bestellung_id']}">
    <p class="fs-5 mb-0 px-4 py-3">Bestellung - Tisch {$eachOrderInProgress['fk_pk_tischnr_id']}</p>
</button>

<div class="modal fade" id="modal{$eachOrderInProgress['fk_pk_tischnr_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Bestellung - Tisch {$eachOrderInProgress['fk_pk_tischnr_id']}</h3>
            </div>
            <div class="row">
                <div class="col">
                    <p class="mx-4">Information:</p>
                    <ul class="mx-2">
BODY;
    foreach ($drinksOfOrderInProgress as $drinkOfOrderInProgress) {
        $body .= <<<BODY
                        <li>{$drinkOfOrderInProgress['anzahl']} {$drinkOfOrderInProgress['bezeichnung']} {$drinkOfOrderInProgress['menge']}l</li>
BODY;
    }
    $body .= <<<BODY
                        <hr style="margin: 5px;">
BODY;

    foreach ($foodOfOrderInProgress as $foodOfOIP){
        $body .= <<<BODY
                        <li>{$foodOfOIP['anzahl']} {$foodOfOIP['bezeichnung']}</li>
BODY;
    }

    $body .= <<<BODY
                
                    </ul>
                </div>
                <div class="col">
                    <p>Tischgruppe: {$tableGroupOfOrderInProgress[0]['bezeichnung']}</p>
                    <p>Status: </p>
                    <h2 class="text-center">{$eachOrderInProgress['status']}</h2>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <button type="button" class="btn bg-secondary fs-5 text-white" data-bs-dismiss="modal">Zurück</button>
                <form method="get">
                    <button type="submit" class="btn bg-secondary fs-5 text-white" name="buttonOrderFinished"
                    value="{$eachOrderInProgress['pk_bestellung_id']}">Bestellung fertig</button>
                </form>
            </div>
        </div>
    </div>
</div>
BODY;
}

if(isset($_GET['buttonOrderFinished'])){
    DB_Order::updateStatusOfOrder('Abholbereit', $_GET['buttonOrderFinished']);
    header("Location: kueche.php");
}

$body .= <<<BODY
</div>
<div class="mx-5 my-5">
    <h4><li>fertige Bestellungen</li></h4>
BODY;

foreach ($allOrdersFinished as $eachOrderFinished){

    $foodOfOrderFinished = DB_Table::getFoodOfTable($eachOrderFinished['fk_pk_tischnr_id']);
    $drinksOfOrderFinished = DB_Table::getDrinksOfTable($eachOrderFinished['fk_pk_tischnr_id']);
    $tableGroupOfOrderFinished = DB_Table::getTableGroupOfTable($eachOrderFinished['fk_pk_tischnr_id']);

    $body .= <<<BODY
<button class="table-kitchen bg-green d-inline-block m-2" id="order_{$eachOrderFinished['pk_bestellung_id']}" 
data-bs-toggle="modal" data-bs-target="#modal{$eachOrderFinished['pk_bestellung_id']}">
    <p class="fs-5 mb-0 px-4 py-3">Bestellung - Tisch {$eachOrderFinished['fk_pk_tischnr_id']}</p>
</button>

<div class="modal fade" id="modal{$eachOrderFinished['fk_pk_tischnr_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Bestellung - Tisch {$eachOrderFinished['fk_pk_tischnr_id']}</h3>
            </div>
            <div class="row">
                <div class="col">
                    <p class="mx-4">Information:</p>
                    <ul class="mx-2">
BODY;
    foreach ($drinksOfOrderFinished as $drinkOfOrderFinished) {
        $body .= <<<BODY
                        <li>{$drinkOfOrderFinished['anzahl']} {$drinkOfOrderFinished['bezeichnung']} {$drinkOfOrderFinished['menge']}l</li>
BODY;
    }
    $body .= <<<BODY
                        <hr style="margin: 5px;">
BODY;

    foreach ($foodOfOrderFinished as $foodOfOF){
        $body .= <<<BODY
                        <li>{$foodOfOF['anzahl']} {$foodOfOF['bezeichnung']}</li>
BODY;
    }

    $body .= <<<BODY
                
                    </ul>
                </div>
                <div class="col">
                    <p>Tischgruppe: {$tableGroupOfOrderFinished[0]['bezeichnung']}</p>
                    <p>Status: </p>
                    <h2 class="text-center">{$eachOrderFinished['status']}</h2>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <button type="button" class="btn bg-secondary fs-5 text-white" data-bs-dismiss="modal">Zurück</button>
                <form method="get">
                    <button type="submit" class="btn bg-secondary fs-5 text-white" name="buttonOrderPickedUp"
                    value="{$eachOrderFinished['pk_bestellung_id']}">Abgeholt</button>
                </form>
            </div>
        </div>
    </div>
</div>
BODY;
}

if(isset($_GET['buttonOrderPickedUp'])){
    DB_Order::updateStatusOfOrder('Serviert', $_GET['buttonOrderPickedUp']);
    header("Location: kueche.php");
}

$body .= <<<BODY
</div>
BODY;

print(SiteTemplate::render('Küche - EGS', $nav, $body));
