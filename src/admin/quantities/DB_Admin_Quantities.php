<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_Quantities
{

    static function getQuantities(): array
    {
        $DB = DB::getDB();
        $quantityAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_menge_id, wert FROM Menge");
            if ($stmt->execute()) {
                $quantityAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $quantityAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function createQuantity(float $value)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Menge (wert) VALUE (:value)");
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteQuantity(int $quantityId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM Menge WHERE pk_menge_id = :quantityId");
            $stmt->bindParam(':quantityId', $quantityId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateQuantityValue(int $quantityId, float $value)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Menge SET wert = :value WHERE pk_menge_id = :quantityId");
            $stmt->bindParam(':quantityId', $quantityId);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}