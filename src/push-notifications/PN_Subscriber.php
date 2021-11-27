<?php
require __DIR__ . '/../vendor/autoload.php';
require_once 'PN_DB_Subscription.php';

$subscription = json_decode(file_get_contents('php://input'));


if (!isset($subscription->endpoint)) {
    echo 'Error: not a subscription';
    return;
}

$method = $_SERVER['REQUEST_METHOD'];

$PN_SubscriptionsDB = new PN_SubscriptionsDB();

switch ($method) {
    case 'POST':
        PN_SubscriptionsDB::saveSubscription($subscription->endpoint, $subscription->publicKey, $subscription->authToken, $subscription->contentEncoding);
        break;
    case 'PUT':
        $PN_SubscriptionsDB::updateSubscriptionPublicKey($subscription->endpoint, $subscription->publicKey);
        $PN_SubscriptionsDB::updateSubscriptionAuthToken($subscription->endpoint, $subscription->authToken);
        break;
    case 'DELETE':
        PN_SubscriptionsDB::deleteSubscription($subscription->endpoint);
        break;
    default:
        echo "Error: method not handled";
        return;
}
