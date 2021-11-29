<?php

namespace easyGastro\push_notifications;

use ErrorException;

require __DIR__ . '/../vendor/autoload.php';
require_once 'PN_DB_Subscription.php';
require_once 'PN_Send.php';


if (!isset($_GET['type'])) {
    return;
}

$PN_DB_Subscription = new PN_DB_Subscription();

switch ($_GET['type']) {
    case 'all':
        $subscriptions = $PN_DB_Subscription->getSubscriptions();
        break;
    case 'userId':
        if (isset($_GET['id'])) {
            $subscriptions = $PN_DB_Subscription->getSubscriptionOfUser($_GET['id']);
        }
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
