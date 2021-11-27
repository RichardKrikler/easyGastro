<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'db/DB_PN_Subscription.php';
require_once 'PN_Send.php';


if (!isset($_GET['type'])) {
    return;
}

switch ($_GET['type']) {
    case 'all':
        $subscriptions = (new PN_SubscriptionsDB())->getSubscriptions();
        break;
    default:
        return;
}


$PN_Send = new PN_Send();

try {
    $PN_Send->send($subscriptions, 'Hallo');
} catch (ErrorException $e) {
    print_r($e);
}
