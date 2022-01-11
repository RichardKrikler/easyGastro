<?php

namespace easyGastro\waiter;

use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';
require_once 'waiter/DB_Table.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Kellner', $row);


$header = <<<HEADER
<div class="header container-fluid mx-0">
    <div class="row">
        <p class="invisible col"></p>
        <h1 class="fw-normal py-3 fs-3 mb-0 col text-center"><a href="/kellner.php" class="text-white text-decoration-none">Kellnerseite</a></h1>
        <form method="post" class="d-flex flex-column justify-content-center my-auto col">
            <button type="submit" name="logout" id="logoutBt" class="shadow-none bg-unset d-flex flex-column justify-content-center">
                <span class="icon material-icons-outlined mx-2 px-2 text-white text-end">logout</span>
            </button>
        </form>
    </div>
</div>
HEADER;

$tableGroup = DB_Table::getTableGroupOfWaiter($_SESSION['user']['name']);
$allTables = DB_Table::getTableIDs($tableGroup[0]['bezeichnung']);

$body = <<<BODY
<script src="waiter/waiter.js" defer></script>
<p class="text-center fw-normal py-3 fs-3">Tischgruppe: {$tableGroup[0]['bezeichnung']}</p>
<div class="container text-center" id="tableContainer">
<div class="row row-cols-2 row-cols-lg-3 row-cols-xl-4">
BODY;

foreach ($allTables as $eachTable) {
    $foodOfTable = DB_Table::getFoodOfTable($eachTable['pk_tischnr_id']);
    $drinksOfTable = DB_Table::getDrinksOfTable($eachTable['pk_tischnr_id']);
    if (!empty($foodOfTable) || !empty($drinksOfTable)) {
        $statusOfTable = 'red';
        $disable = "data-bs-toggle='modal' data-bs-target='#modal{$eachTable['pk_tischnr_id']}'";
    } else {
        $statusOfTable = 'green';
        $disable = 'disabled';
    }
    $body .= <<<BODY
<!-- Modal -->
<div class="col">
    <button class="my-3 w-100 table-waiter bg-$statusOfTable" id="table_{$eachTable['pk_tischnr_id']}" $disable>
        <p class="fs-5 mb-0 px-2 py-2">Tisch {$eachTable['pk_tischnr_id']}</p>
    </button>
</div>

<div class="modal fade" id="modal{$eachTable['pk_tischnr_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0">
            <div class="modal-header d-flex justify-content-center border-bottom-0">
                <h3 class="modal-title">Tisch {$eachTable['pk_tischnr_id']}</h3>
            </div>
            <div class="modal-body text-start">
                <!-- Table Element -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="fw-normal fs-5 text-center">Anz.</th>
                            <th scope="col" class="fw-normal fs-5 text-center">Bezeichnung</th>
                            <th scope="col" class="fw-normal fs-5 text-center">Preis</th>
                        </tr>
                    </thead>
                    
                    <tbody>
BODY;
    $fullPrice = 0;
    foreach ($drinksOfTable as $drink) {
        $body .= <<<BODY
                        <tr>
                            <td class="text-center">{$drink['anzahl']}</td>
                            <td>{$drink['bezeichnung']} {$drink['menge']}</td>
                            <td class="text-center">{$drink['GSPreis']}</td>
                        </tr>
BODY;
        $fullPrice += $drink['GSPreis'];
    }
    foreach ($foodOfTable as $food) {
        $body .= <<<BODY
                        <tr>
                            <td class="text-center">{$food['anzahl']}</td>
                            <td>{$food['bezeichnung']}</td>
                            <td class="text-center">{$food['GSPreis']}</td>
                        </tr>
BODY;
        $fullPrice += $food['GSPreis'];
    }
    $fullPrice = number_format((float)$fullPrice, 2, '.', '');
    $body .= <<<BODY
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between mx-5 mt-3">
                <p class="fs-4 fw-bold">Gesamt</p>
                <p class="fs-4 fw-bold full-price-text" table="{$eachTable['pk_tischnr_id']}">$fullPrice</p>
            </div>
            <div class="d-flex justify-content-between mx-5">
                <div>
                    <label for="givenMoney">Kunde</label><br>
                    <input class="border border-dark fs-4 text-center given-money-field" type="number" name="givenMoney" table="{$eachTable['pk_tischnr_id']}">
                </div>
                <div>
                    <label for="backMoney">Kellner</label><br>
                    <input class="border border-dark fs-4 text-center back-money-field" type="text" id="backMoney" name="backMoney" size="3" disabled table="{$eachTable['pk_tischnr_id']}">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between border-top-0">
                <button type="button" class="btn bg-secondary fs-5 text-white" data-bs-dismiss="modal">Zurück</button>
                <form method="get">
                    <button type="submit" class="btn bg-secondary fs-5 text-white" name="clearTable" 
                    value="{$eachTable['pk_tischnr_id']}">Tisch räumen</button>
                </form>
            </div>
        </div>
    </div>
</div>
BODY;
}

if (isset($_GET['clearTable'])) {
    DB_Table::updateStatusOfTable('Bezahlt', date('Y-m-d H:i:s'), DB_Table::getPkOfOrder($_GET['clearTable'])[0]['pk_bestellung_id']);
    header("Location: kellner.php");
}

$body .= <<<BODY
</div>
</div>
<br>

<div class="text-center">
    <script src="/push-notifications/PN_Subscription.js" defer></script>
    <button id="push-subscription-button" class="btn bg-red my-3 fs-5 py-2 px-3">Push notifications!</button>
    <script defer>const userId = "{$_SESSION['user']['id']}"</script>
</div>
BODY;

print(SiteTemplate::render('Kellner - EGS', $header, $body));
