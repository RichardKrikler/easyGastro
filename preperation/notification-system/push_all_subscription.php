<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'db/PN_SubscriptionDB.php';
require_once 'PN_Send.php';

$subscriptions = (new PN_SubscriptionsDB())->getSubscriptions();
$PN_Send = new PN_Send();

try {
    $PN_Send->send($subscriptions, 'Hallo');
} catch (ErrorException $e) {
    print_r($e);
}
