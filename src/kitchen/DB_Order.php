<?php

namespace easyGastro\kitchen;

use easyGastro\DB;
use PDO;
use PDOException;


require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db.php';

class DB_Order
{
    static function getAllOrders()
    {
        $DB = DB::getDB();
        $orders = array();
        try {
            $stmt = $DB->prepare('SELECT bestellung.*
                                        FROM bestellung');
            if ($stmt->execute()) {
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $orders;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getOrders($status)
    {
        $DB = DB::getDB();
        $orders = array();
        try {
            $stmt = $DB->prepare('SELECT bestellung.*
                                        FROM bestellung
                                        WHERE status = :statusOfOrder');
            $stmt->bindParam(':statusOfOrder', $status);
            if ($stmt->execute()) {
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $orders;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateStatusOfOrder($status, $orderID)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare('UPDATE bestellung SET status = ? WHERE pk_bestellung_id = ?');
            $stmt->execute([$status,$orderID]);
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}