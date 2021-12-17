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
require_once 'kitchen\OrderButton.php';


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
BODY;

$body .= OrderButton::createOrderButton('neue Bestellungen',$allOrdersOpen,'changeToProgress','In-Bearbeitung','In-Bearbeitung setzen', 'red');

$body .= OrderButton::createOrderButton('Bestellungen in Arbeit',$allOrdersInProgress,'changeToReady','Abholbereit','Bestellung fertig','yellow');

$body .= OrderButton::createOrderButton('fertige Bestellungen',$allOrdersFinished,'changeToServed','Serviert','Abgeholt','green');

print(SiteTemplate::render('Küche - EGS', $nav, $body));
