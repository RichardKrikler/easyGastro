<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_DrinkGroups
{

    static function getDrinkGroups(): array
    {
        $DB = DB::getDB();
        $drinkGroupAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_getraenkegrp_id, bezeichnung FROM Getraenkegruppe");
            if ($stmt->execute()) {
                $drinkGroupAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $drinkGroupAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function createDrinkGroup(string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Getraenkegruppe (bezeichnung) VALUE (:name)");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteDrinkGroup(int $drinkGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM Getraenkegruppe WHERE pk_getraenkegrp_id = :drinkGroupId");
            $stmt->bindParam(':drinkGroupId', $drinkGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDrinkGroup(int $drinkGroupId, string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Getraenkegruppe SET bezeichnung = :name WHERE pk_getraenkegrp_id = :drinkGroupId");
            $stmt->bindParam(':drinkGroupId', $drinkGroupId);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}