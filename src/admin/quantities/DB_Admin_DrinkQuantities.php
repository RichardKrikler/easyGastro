<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_DrinkQuantities
{
    static function getDrinkQuantities(): array
    {
        $DB = DB::getDB();
        $drinkQuantityAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_getraenkmg_id, preis, fk_pk_getraenk_id, fk_pk_menge_id FROM Getraenk_Menge");
            if ($stmt->execute()) {
                $drinkQuantityAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $drinkQuantityAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function createDrinkQuantity(int $drinkId, int $quantityId, float $price)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Getraenk_Menge (preis, fk_pk_getraenk_id, fk_pk_menge_id) VALUE (:price, :drinkId, :quantityId)");
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':drinkId', $drinkId);
            $stmt->bindParam(':quantityId', $quantityId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteDrinkQuantity(int $drinkQuantityId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM Getraenk_Menge WHERE pk_getraenkmg_id = :drinkQuantityId");
            $stmt->bindParam(':drinkQuantityId', $drinkQuantityId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDrinkQuantityPrice(int $drinkQuantityId, float $price)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Getraenk_Menge SET preis = :price WHERE pk_getraenkmg_id = :drinkQuantityId");
            $stmt->bindParam(':drinkQuantityId', $drinkQuantityId);
            $stmt->bindParam(':price', $price);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDrinkQuantityDrink(int $drinkQuantityId, int $drinkId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Getraenk_Menge SET fk_pk_getraenk_id = :drinkId WHERE pk_getraenkmg_id = :drinkQuantityId");
            $stmt->bindParam(':drinkQuantityId', $drinkQuantityId);
            $stmt->bindParam(':drinkId', $drinkId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDrinkQuantityQuantity(int $drinkQuantityId, int $quantityId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Getraenk_Menge SET fk_pk_menge_id = :quantityId WHERE pk_getraenkmg_id = :drinkQuantityId");
            $stmt->bindParam(':drinkQuantityId', $drinkQuantityId);
            $stmt->bindParam(':quantityId', $quantityId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}