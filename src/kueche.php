<?php

namespace easyGastro;

use easyGastro\kitchen\DB_Order;
use easyGastro\kitchen\OrderButton;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';
require_once 'kitchen/DB_Order.php';
require_once 'kitchen/OrderButton.php';
require_once 'waiter/DB_Table.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Küchenmitarbeiter', $row);


$header = <<<HEADER
<div class="header container-fluid mx-0">
    <div class="row">
        <p class="invisible col"></p>
        <h1 class="fw-normal py-3 fs-3 mb-0 col text-center"><a href="/kueche.php" class="text-white text-decoration-none">Küchenseite</a></h1>
        <form method="post" class="d-flex flex-column justify-content-center my-auto col">
            <button type="submit" name="logout" id="logoutBt" class="shadow-none bg-unset d-flex flex-column justify-content-center">
                <span class="icon material-icons-outlined mx-2 px-2 text-white text-end align-self-end">logout</span>
            </button>
        </form>
    </div>
</div>
HEADER;

$allOrdersOpen = DB_Order::getOrders("Offen");
$allOrdersInProgress = DB_Order::getOrders("In-Bearbeitung");
$allOrdersFinished = DB_Order::getOrders("Abholbereit");

$body = '<script src="kitchen/kitchen.js" defer></script>';

$body .= OrderButton::createOrderButton('neue Bestellungen', $allOrdersOpen, 'changeToProgress', 'In-Bearbeitung', 'In-Bearbeitung setzen', 'red');

$body .= OrderButton::createOrderButton('Bestellungen in Arbeit', $allOrdersInProgress, 'changeToReady', 'Abholbereit', 'Bestellung fertig', 'yellow');

$body .= OrderButton::createOrderButton('fertige Bestellungen', $allOrdersFinished, 'changeToServed', 'Serviert', 'Abgeholt', 'green');

print(SiteTemplate::render('Küche - EGS', $header, $body));