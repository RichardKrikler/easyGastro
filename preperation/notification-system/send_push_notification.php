<?php
require __DIR__ . '/vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$subscription = Subscription::create(json_decode(file_get_contents('php://input'), true));

$auth = array(
    'VAPID' => array(
        'subject' => file_get_contents(__DIR__ . '/keys/subject.txt'),
        'publicKey' => file_get_contents(__DIR__ . '/keys/public_key.txt'),
        'privateKey' => file_get_contents(__DIR__ . '/keys/private_key.txt'),
    ),
);

$webPush = new WebPush($auth);

$report = $webPush->sendOneNotification(
    $subscription,
    "Hello! 👋"
);

// handle eventual errors here, and remove the subscription from your server if it is expired
$endpoint = $report->getRequest()->getUri()->__toString();

if ($report->isSuccess()) {
    echo "[v] Message sent successfully for subscription {$endpoint}.";
} else {
    echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
}
