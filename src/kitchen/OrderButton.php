<?php

namespace easyGastro\kitchen;

use easyGastro\push_notifications\PN_DB_Subscription;
use easyGastro\push_notifications\PN_Send;
use easyGastro\waiter\DB_Table;

require_once __DIR__ . '/../push-notifications/PN_Send.php';
require_once __DIR__ . '/../push-notifications/PN_DB_Subscription.php';
require_once __DIR__ . '/../waiter/DB_Table.php';

class OrderButton
{
    /**
     * @throws \ErrorException
     */
    static function createOrderButton($whichOrders, $orders, $buttonName, $newStatus, $buttonText, $color)
    {
        $body = <<<BODY
        <div class="mx-5 my-5">
            <h4><li>{$whichOrders}</li></h4>
BODY;

        foreach ($orders as $eachOrder) {

            $foodOfOrder = DB_Table::getFoodOfTable($eachOrder['fk_pk_tischnr_id']);
            $drinksOfOrder = DB_Table::getDrinksOfTable($eachOrder['fk_pk_tischnr_id']);
            $tableGroupOfOrder = DB_Table::getTableGroupOfTable($eachOrder['fk_pk_tischnr_id']);

            $body .= <<<BODY
            <button class="table-kitchen bg-{$color} d-inline-block m-2" id="order_{$eachOrder['pk_bestellung_id']}" 
            data-bs-toggle="modal" data-bs-target="#modal{$eachOrder['pk_bestellung_id']}">
                <p class="fs-5 mb-0 px-4 py-3">Bestellung - Tisch {$eachOrder['fk_pk_tischnr_id']}</p>
            </button>
            
            <div class="modal fade" id="modal{$eachOrder['pk_bestellung_id']}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header d-flex justify-content-center border-bottom-0">
                            <h3 class="modal-title">Bestellung - Tisch {$eachOrder['fk_pk_tischnr_id']}</h3>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="mx-4">Information:</p>
                                <ul class="mx-2">
BODY;
            foreach ($drinksOfOrder as $eachDrink) {
                $body .= <<<BODY
                                    <li>{$eachDrink['anzahl']} {$eachDrink['bezeichnung']} {$eachDrink['menge']}l</li>
BODY;
            }
            $body .= <<<BODY
                                    <hr style="margin: 5px;">
BODY;

            foreach ($foodOfOrder as $food) {
                $body .= <<<BODY
                                    <li>{$food['anzahl']} {$food['bezeichnung']}</li>
BODY;
            }

            $body .= <<<BODY
                
                                </ul>
                            </div>
                            <div class="col">
                                <p>Tischgruppe: {$tableGroupOfOrder[0]['bezeichnung']}</p>
                                <p>Status: </p>
                                <h2 class="text-center">{$eachOrder['status']}</h2>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between border-top-0">
                            <button type="button" class="btn bg-secondary fs-5 text-white" data-bs-dismiss="modal">Zurück</button>
                            <form method="get">
                                <button type="submit" class="btn bg-secondary fs-5 text-white" name="{$buttonName}"
                                value="{$eachOrder['pk_bestellung_id']}.{$eachOrder['fk_pk_tischnr_id']}">{$buttonText}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
BODY;
        }

        if (isset($_GET[$buttonName])) {
            $orderID = strtok($_GET[$buttonName], '.');
            $tableID = strtok('');
            DB_Order::updateStatusOfOrder($newStatus, $orderID);
            if ($newStatus === 'Abholbereit') {
                (new PN_Send())->send(PN_DB_Subscription::getSubscriptionsOfTableGroup($tableGroupOfOrder[0]['pk_tischgrp_id']), '{"msg": "Bestellung - Tisch ' . $tableID . ': Abholbereit!", "data": "' . $tableID . '"}');
            }
            header("Location: kueche.php");
        }

        $body .= <<<BODY
        </div>
BODY;
        return $body;
    }
}