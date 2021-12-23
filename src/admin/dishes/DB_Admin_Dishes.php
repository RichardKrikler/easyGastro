<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_Dishes
{

    static function getDishes()
    {
        $DB = DB::getDB();
        $dishAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_speise_id, bezeichnung, preis, fk_pk_speisegrp_id FROM Speise");
            if ($stmt->execute()) {
                $dishAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dishAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    public static function createDish(string $name, float $price, int $dishGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Speise (bezeichnung, preis, fk_pk_speisegrp_id) VALUE (:name, :price, :dishGroupId)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':dishGroupId', $dishGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteDish(int $dishId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM Speise WHERE pk_speise_id = :dishId");
            $stmt->bindParam(':dishId', $dishId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDishName(int $dishId, string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Speise SET bezeichnung = :name WHERE pk_speise_id = :dishId");
            $stmt->bindParam(':dishId', $dishId);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDishPrice(int $dishId, float $price)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Speise SET preis = :price WHERE pk_speise_id = :dishId");
            $stmt->bindParam(':dishId', $dishId);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateDishGroupId(int $dishId, int $dishGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Speise SET fk_pk_speisegrp_id = :dishGroupId WHERE pk_speise_id = :dishId");
            $stmt->bindParam(':dishId', $dishId);
            $stmt->bindParam(':dishGroupId', $dishGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }


}