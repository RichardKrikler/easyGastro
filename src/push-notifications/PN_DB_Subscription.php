<?php

namespace easyGastro\push_notifications;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db.php';

class PN_SubscriptionsDB
{
    static function getSubscriptions()
    {
        $DB = DB::getDB();
        $subscriptionsJSON = '';
        try {
            $stmt = $DB->prepare('SELECT endpoint, authToken, publicKey, contentEncoding, fk_pk_user_id FROM PN_Subscriptions');
            if ($stmt->execute()) {
                $subscriptionsJSON = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $subscriptionsJSON;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function saveSubscription($endpoint, $publicKey, $authToken, $contentEncoding, $userId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare('INSERT INTO PN_Subscriptions (endpoint, publicKey, authToken, contentEncoding, fk_pk_user_id) VALUE (:endpoint, :publicKey, :authToken, :contentEncoding, :userId)');
            $stmt->bindParam(":endpoint", $endpoint);
            $stmt->bindParam(":publicKey", $publicKey);
            $stmt->bindParam(":authToken", $authToken);
            $stmt->bindParam(":contentEncoding", $contentEncoding);
            $stmt->bindParam(":userId", $userId);
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
