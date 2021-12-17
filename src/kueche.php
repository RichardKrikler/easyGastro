<?php

namespace easyGastro;

use easyGastro\kitchen\DB_Order;
use easyGastro\push_notifications\PN_DB_Subscription;
use easyGastro\push_notifications\PN_Send;
use easyGastro\waiter\DB_Table;
use easyGastro\kitchen\OrderButton;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';
require_once 'kitchen/DB_Order.php';
require_once 'waiter/DB_Table.php';
require_once 'push-notifications/PN_Send.php';
require_once 'push-notifications/PN_DB_Subscription.php';
require_once 'kitchen/OrderButton.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Küchenmitarbeiter', $row);


$header = <<<HEADER
<div class="header d-flex justify-content-between">
    <p class="invisible"></p>
    <h1 class="fw-normal py-3 fs-3 mb-0"><a href="/kueche.php" class="text-white text-decoration-none">Küchenseite</a></h1>
    <form method="post" class="d-flex flex-column justify-content-center my-auto">
        <button type="submit" name="logout" id="logoutBt" class="shadow-none bg-unset d-flex flex-column justify-content-center">
            <span class="icon material-icons-outlined mx-2 px-2 text-white">logout</span>
        </button>
    </form>
</div>
HEADER;

$allOrdersOpen = DB_Order::getOrders("Offen");
$allOrdersInProgress = DB_Order::getOrders("In-Bearbeitung");
$allOrdersFinished = DB_Order::getOrders("Abholbereit");

$body = <<<BODY
<script src="kitchen/kitchen.js" defer></script>
BODY;


foreach ($allOrdersOpen as $eachOrderOpen) {

$body .= OrderButton::createOrderButton('Bestellungen in Arbeit',$allOrdersInProgress,'changeToReady','Abholbereit','Bestellung fertig','yellow');

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

    foreach ($foodOfOrderOpen as $foodOfOO) {
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

if (isset($_GET['buttonInProgress'])) {
    DB_Order::updateStatusOfOrder('In-Bearbeitung', $_GET['buttonInProgress']);
    header("Location: kueche.php");
}

$body .= <<<BODY
</div>
<div class="mx-5 my-5">
    <h4><li>Bestellungen in Arbeit</li></h4>
BODY;

foreach ($allOrdersInProgress as $eachOrderInProgress) {

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

    foreach ($foodOfOrderInProgress as $foodOfOIP) {
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

if (isset($_GET['buttonOrderFinished'])) {
    DB_Order::updateStatusOfOrder('Abholbereit', $_GET['buttonOrderFinished']);
    header("Location: kueche.php");
}

$body .= <<<BODY
</div>
<div class="mx-5 my-5">
    <h4><li>fertige Bestellungen</li></h4>
BODY;

foreach ($allOrdersFinished as $eachOrderFinished) {

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

    foreach ($foodOfOrderFinished as $foodOfOF) {
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

if (isset($_GET['buttonOrderPickedUp'])) {
    DB_Order::updateStatusOfOrder('Serviert', $_GET['buttonOrderPickedUp']);
    header("Location: kueche.php");
}

$body .= <<<BODY
</div>
BODY;

print(SiteTemplate::render('Küche - EGS', $header, $body));
