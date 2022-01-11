<?php

namespace easyGastro\Customer;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db.php';

use easyGastro\DB;
use Exception;
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

    static function sendOrder($tablePK, $order)
    {
        $DB = DB::getDB();
        $orderPKs = array();
        try {
            $orderPKsStmt = $DB->prepare("SELECT pk_bestellung_id FROM bestellung");
            if ($orderPKsStmt->execute()) {
                $orderPKs = $orderPKsStmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $newOrderPK = intval($orderPKs[sizeof($orderPKs) - 1]['pk_bestellung_id']) + 1;
            $timestamp = date("Y-m-d H:i:s");
            $status = 'Offen';
            $newOrderStmt = $DB->prepare("INSERT INTO bestellung (pk_bestellung_id, pk_timestamp_von, status, fk_pk_tischnr_id) 
                                                VALUES ($newOrderPK, '$timestamp', '$status', $tablePK);");
            $newOrderStmt->execute();
            foreach ($order as $orderKey) {
                if (sizeof($orderKey) == 4) {
                    $newFoodOrder = $DB->prepare("INSERT INTO bestellung_speise (pk_fk_pk_bestellung_id, pk_fk_pk_speise, anzahl)
                                                        VALUES ($newOrderPK, $orderKey[0], $orderKey[2]);");
                    $newFoodOrder->execute();
                } else if (sizeof($orderKey) == 5) {
                    $newDrinkOrder = $DB->prepare("INSERT INTO bestellung_getraenkmenge (pk_fk_pk_bestellung_id, pk_fk_pk_getraenkmg_id, anzahl)
                                                        VALUES ($newOrderPK, $orderKey[0], $orderKey[2]);");
                    $newDrinkOrder->execute();
                } else {
                    throw new Exception('INVALID ORDER');
                }
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    /**
     * @throws Exception
     */
    static function getTablePK($tableCode)
    {
        $DB = DB::getDB();
        $tables = array();
        try {
            $stmt = $DB->prepare("SELECT * FROM tisch");
            if ($stmt->execute()) {
                $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            foreach ($tables as $table) {
                if ($table['tischcode'] == $tableCode) {
                    return $table['pk_tischnr_id'];
                }
            }
            throw new Exception('TABLE NOT EXISTING');
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getTableGroup($tablePK) {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("SELECT fk_pk_tischgrp_id FROM tisch WHERE pk_tischnr_id = $tablePK");
            if ($stmt->execute()) {
                return intval($stmt->fetchAll(PDO::FETCH_ASSOC)[0]['fk_pk_tischgrp_id']);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            throw new Exception('CANNOT FIND TABLEGROUP');
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}