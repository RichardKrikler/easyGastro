<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'db/PN_SubscriptionDB.php';
require_once 'PN_Send.php';

$PN_SubscriptionsDB = new PN_SubscriptionsDB();
$subscriptions = $PN_SubscriptionsDB->getSubscriptions();

$PN_Send = new PN_Send();
$PN_Send->sendPN($subscriptions, 'Hallo');
