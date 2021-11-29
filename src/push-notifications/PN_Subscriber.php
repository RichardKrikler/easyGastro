<?php

namespace easyGastro\push_notifications;

require __DIR__ . '/../vendor/autoload.php';
require_once 'PN_DB_Subscription.php';

$subscription = json_decode(file_get_contents('php://input'));


if (!isset($subscription->endpoint)) {
    echo 'Error: not a subscription';
    return;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        PN_DB_Subscription::saveSubscription($subscription->endpoint, $subscription->publicKey, $subscription->authToken, $subscription->contentEncoding, $subscription->userId);
        break;
    case 'PUT':
        PN_DB_Subscription::updateSubscriptionPublicKey($subscription->endpoint, $subscription->publicKey);
        PN_DB_Subscription::updateSubscriptionAuthToken($subscription->endpoint, $subscription->authToken);
        break;
    case 'DELETE':
        PN_DB_Subscription::deleteSubscription($subscription->endpoint);
        break;
    default:
        echo "Error: method not handled";
        return;
}
