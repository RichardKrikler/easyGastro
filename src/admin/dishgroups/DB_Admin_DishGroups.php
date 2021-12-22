<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_DishGroups
{

    static function getDishGroups(): array
    {
        $DB = DB::getDB();
        $dishGroupAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_speisegrp_id, bezeichnung FROM Speisegruppe");
            if ($stmt->execute()) {
                $dishGroupAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dishGroupAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function createDishGroup(string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Speisegruppe (bezeichnung) VALUE (:name)");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteDishGroup(int $dishGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM Speisegruppe WHERE pk_speisegrp_id = :dishGroupId");
            $stmt->bindParam(':dishGroupId', $dishGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDishGroup(int $dishGroupId, string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Speisegruppe SET bezeichnung = :name WHERE pk_speisegrp_id = :dishGroupId");
            $stmt->bindParam(':dishGroupId', $dishGroupId);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}