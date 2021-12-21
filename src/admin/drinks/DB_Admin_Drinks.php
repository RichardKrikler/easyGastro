<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_Drinks
{
    static function getDrinks()
    {
        $DB = DB::getDB();
        $drinkAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_getraenk_id, bezeichnung, fk_pk_getraenkegrp_id FROM Getraenk");
            if ($stmt->execute()) {
                $drinkAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $drinkAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function createDrink(string $name, int $drinkGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Getraenk (bezeichnung, fk_pk_getraenkegrp_id) VALUE (:name, :drinkGroupId)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':drinkGroupId', $drinkGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteDrink(int $drinkId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM Getraenk WHERE pk_getraenk_id = :drinkId");
            $stmt->bindParam(':drinkId', $drinkId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDrinkName(int $drinkId, string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Getraenk SET bezeichnung = :name WHERE pk_getraenk_id = :drinkId");
            $stmt->bindParam(':drinkId', $drinkId);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDrinkGroupId($drinkId, $drinkGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Getraenk SET fk_pk_getraenkegrp_id = :drinkGroupId WHERE pk_getraenk_id = :drinkId");
            $stmt->bindParam(':drinkId', $drinkId);
            $stmt->bindParam(':drinkGroupId', $drinkGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

}