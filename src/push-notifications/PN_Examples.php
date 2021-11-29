<?php

namespace easyGastro\push_notifications;

require __DIR__ . '/../vendor/autoload.php';
require_once 'PN_DB_Subscription.php';
require_once 'PN_Send.php';


if (!isset($_GET['type'])) {
    return;
}


switch ($_GET['type']) {
    case 'all':
        $subscriptions = PN_DB_Subscription::getSubscriptions();
        break;
    case 'userId':
        if (isset($_GET['id'])) {
            $subscriptions = PN_DB_Subscription::getSubscriptionOfUser($_GET['id']);
        }
        break;
    case 'userType':
        if (isset($_GET['typ'])) {
            $subscriptions = PN_DB_Subscription::getSubscriptionsOfUserType($_GET['typ']);
        }
        break;
    case 'tableGroup':
        if (isset($_GET['group'])) {
            $subscriptions = PN_DB_Subscription::getSubscriptionsOfTableGroup($_GET['group']);
        }
        break;
    default:
        print('type not available');
        return;
}


$PN_Send = new PN_Send();

$PN_Send->send($subscriptions, 'Hallo');

// in one line:
// (new PN_Send())->send((new PN_DB_Subscription())->getSubscriptions(), 'Hallo');
