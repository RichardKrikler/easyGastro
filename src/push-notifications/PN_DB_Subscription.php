<?php

namespace easyGastro\push_notifications;

use easyGastro\DB;
use PDO;
use PDOException;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db.php';

class PN_DB_Subscription
{
    static function getSubscriptions()
    {
        $DB = DB::getDB();
        $subscriptionsJSON = '';
        try {
            $stmt = $DB->prepare('SELECT endpoint, authToken, publicKey, contentEncoding FROM PN_Subscriptions');
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

    static function saveSubscription(string $endpoint, string $publicKey, string $authToken, string $contentEncoding, int $userId)
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

    static function deleteSubscription(string $endpoint)
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

    static function updateSubscriptionPublicKey(string $endpoint, string $publicKey)
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

    static function updateSubscriptionAuthToken(string $endpoint, string $authToken)
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

    static function getSubscriptionOfUser(int $userId)
    {
        $DB = DB::getDB();
        $subscriptionsJSON = '';
        try {
            $stmt = $DB->prepare('SELECT endpoint, authToken, publicKey, contentEncoding FROM PN_Subscriptions WHERE fk_pk_user_id = :userId');
            $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
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

    static function getSubscriptionsOfUserType(string $userType)
    {
        $DB = DB::getDB();
        $subscriptionsJSON = '';
        try {
            $stmt = $DB->prepare('SELECT endpoint, authToken, publicKey, contentEncoding FROM PN_Subscriptions INNER JOIN User ON pk_user_id = fk_pk_user_id WHERE typ = :userType');
            $stmt->bindParam(":userType", $userType);
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

    static function getSubscriptionsOfTableGroup(int $tableGroup)
    {
        $DB = DB::getDB();
        $subscriptionsJSON = '';
        try {
            $stmt = $DB->prepare('SELECT endpoint, authToken, publicKey, contentEncoding FROM PN_Subscriptions INNER JOIN User ON pk_user_id = fk_pk_user_id INNER JOIN Kellner ON pk_user_id = pk_fk_pk_user_id WHERE fk_pk_tischgrp_id = :tableGroup');
            $stmt->bindParam(":tableGroup", $tableGroup, PDO::PARAM_INT);
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
}
