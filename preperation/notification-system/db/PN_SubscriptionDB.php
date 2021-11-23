<?php
require __DIR__ . '/../vendor/autoload.php';
require_once 'DB.php';

class PN_SubscriptionsDB
{
    static function getSubscriptions(): array
    {
        $DB = DB::getDB();
        $subscriptions[] = [];
        try {
            $stmt = $DB->prepare('SELECT endpoint, authToken, publicKey FROM PN_Subscriptions');
            if ($stmt->execute()) {
                while ($row = $stmt->fetch()) {
                    $subscriptions[] = [$row['endpoint'], $row['publicKey'], $row['authToken']];
                }
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $subscriptions;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function saveSubscription($endpoint, $publicKey, $authToken)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare('INSERT INTO PN_Subscriptions (endpoint, publicKey, authToken) VALUE (:endpoint, :publicKey, :authToken)');
            $stmt->bindParam(":endpoint", $endpoint);
            $stmt->bindParam(":publicKey", $publicKey);
            $stmt->bindParam(":authToken", $authToken);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteSubscription($endpoint)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare('DELETE FROM PN_Subscriptions WHERE endpoint = :endpoint');
            $stmt->bindParam(":endpoint", $endpoint);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateSubscriptionPublicKey($endpoint, $publicKey)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare('UPDATE PN_Subscriptions SET publicKey = :publicKey WHERE endpoint = :endpoint');
            $stmt->bindParam(":endpoint", $endpoint);
            $stmt->bindParam(":publicKey", $publicKey);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateSubscriptionAuthToken($endpoint, $authToken)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare('UPDATE PN_Subscriptions SET authToken = :authToken WHERE endpoint = :endpoint');
            $stmt->bindParam(":endpoint", $endpoint);
            $stmt->bindParam(":authToken", $authToken);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}
