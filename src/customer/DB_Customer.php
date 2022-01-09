<?php

namespace easyGastro\Customer;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db.php';

use easyGastro\DB;
use PDO;
use PDOException;

class DB_Customer
{
    static function getDrinkGroups()
    {
        $DB = DB::getDB();
        $drinkGroups = array();
        try {
            $stmt = $DB->prepare('SELECT bezeichnung FROM getraenkegruppe;');
            if ($stmt->execute()) {
                $drinkGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $drinkGroups;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getDrinks($drinkGroup)
    {
        $DB = DB::getDB();
        $drinks = array();
        try {
            $stmt = $DB->prepare("SELECT pk_getraenk_id, g.bezeichnung FROM getraenk g
                                        INNER JOIN getraenkegruppe gg on g.fk_pk_getraenkegrp_id = gg.pk_getraenkegrp_id
                                        WHERE gg.bezeichnung = '$drinkGroup';");
            if ($stmt->execute()) {
                $drinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $drinks;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getFoodGroups()
    {
        $DB = DB::getDB();
        $foodGroups = array();
        try {
            $stmt = $DB->prepare('SELECT bezeichnung FROM speisegruppe;');
            if ($stmt->execute()) {
                $foodGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $foodGroups;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getFood($foodGroup)
    {
        $DB = DB::getDB();
        $food = array();
        try {
            $stmt = $DB->prepare("SELECT pk_speise_id, s.bezeichnung FROM speise s
                                        INNER JOIN speisegruppe sg on s.fk_pk_speisegrp_id = sg.pk_speisegrp_id
                                        WHERE sg.bezeichnung = '$foodGroup';");
            if ($stmt->execute()) {
                $food = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $food;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getCompleteFoodList()
    {
        $DB = DB::getDB();
        $food = array();
        try {
            $stmt = $DB->prepare("SELECT * FROM speise");
            if ($stmt->execute()) {
                $food = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $food;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getCompleteDrinkList()
    {
        $DB = DB::getDB();
        $drinks = array();
        try {
            $stmt = $DB->prepare("SELECT * FROM getraenk");
            if ($stmt->execute()) {
                $drinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $drinks;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getCompleteDrinkAmountList()
    {
        $DB = DB::getDB();
        $drinksAmounts = array();
        try {
            $stmt = $DB->prepare("SELECT * FROM getraenk_menge");
            if ($stmt->execute()) {
                $drinksAmounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $drinksAmounts;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getCompleteAmountList()
    {
        $DB = DB::getDB();
        $amounts = array();
        try {
            $stmt = $DB->prepare("SELECT * FROM menge");
            if ($stmt->execute()) {
                $amounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $amounts;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function tableCodeExists($checkTableCode) {
        $DB = DB::getDB();
        $tableCodes = array();
        try {
            $stmt = $DB->prepare("SELECT tischcode FROM tisch");
            if ($stmt->execute()) {
                $tableCodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            foreach ($tableCodes as $tableCode) {
                if ($checkTableCode == $tableCode['tischcode']) {
                    return true;
                }
            }
            return false;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}