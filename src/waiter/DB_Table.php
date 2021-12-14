<?php

namespace easyGastro\waiter;

use easyGastro\DB;
use PDO;
use PDOException;


require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db.php';

class DB_Table
{
    static function getTableIDs($tableGroup)
    {
        $DB = DB::getDB();
        $tableIDs = array();
        try {
            $stmt = $DB->prepare('SELECT DISTINCT pk_tischnr_id FROM tisch
                                        INNER JOIN tischgruppe t on tisch.fk_pk_tischgrp_id = t.pk_tischgrp_id
                                        WHERE t.bezeichnung = :tischgrp');
            $stmt->bindParam(':tischgrp', $tableGroup);
            if ($stmt->execute()) {
                $tableIDs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $tableIDs;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getTableGroupOfWaiter($username)
    {
        $DB = DB::getDB();
        $tableGroup = '';
        try {
            $stmt = $DB->prepare('SELECT bezeichnung FROM tischgruppe
                                        INNER JOIN kellner k on tischgruppe.pk_tischgrp_id = k.fk_pk_tischgrp_id
                                        INNER JOIN user u on k.pk_fk_pk_user_id = u.pk_user_id
                                        WHERE u.name = :name');
            $stmt->bindParam(':name', $username);
            if ($stmt->execute()) {
                $tableGroup = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $tableGroup;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getFoodOfTable($oneTable)
    {
        $DB = DB::getDB();
        $tableItems = array();
        try {
            $stmt = $DB->prepare('SELECT anzahl, bezeichnung, CAST(preis * anzahl AS DECIMAL(8,2)) AS GSPreis
                                        FROM bestellung_speise
                                        INNER JOIN bestellung b on bestellung_speise.pk_fk_pk_bestellung_id = b.pk_bestellung_id
                                        INNER JOIN speise s on bestellung_speise.pk_fk_pk_speise = s.pk_speise_id
                                        WHERE b.fk_pk_tischnr_id = :tischnr AND b.status != \'Bezahlt\'');
            $stmt->bindParam(':tischnr', $oneTable);
            if ($stmt->execute()) {
                $tableItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $tableItems;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getDrinksOfTable($oneTable)
    {
        $DB = DB::getDB();
        $tableItems = array();
        try {
            $stmt = $DB->prepare('SELECT anzahl, bezeichnung, m.wert AS menge, CAST(preis * anzahl AS DECIMAL(8,2)) AS GSPreis
                                        FROM bestellung_getraenkmenge
                                        INNER JOIN bestellung b on bestellung_getraenkmenge.pk_fk_pk_bestellung_id = b.pk_bestellung_id
                                        INNER JOIN getraenk_menge gm on bestellung_getraenkmenge.pk_fk_pk_getraenkmg_id = gm.pk_getraenkmg_id
                                        INNER JOIN getraenk g on gm.fk_pk_getraenk_id = g.pk_getraenk_id
                                        INNER JOIN menge m on gm.fk_pk_menge_id = m.pk_menge_id
                                        WHERE b.fk_pk_tischnr_id = :tischnr AND b.status != \'Bezahlt\'');
            $stmt->bindParam(':tischnr', $oneTable);
            if ($stmt->execute()) {
                $tableItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $tableItems;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getOrders($tableGroup)
    {
        $DB = DB::getDB();
        $tableItems = array();
        try {
            $stmt = $DB->prepare('SELECT status
                                        FROM bestellung
                                        INNER JOIN tisch t on bestellung.fk_pk_tischnr_id = t.pk_tischnr_id
                                        INNER JOIN tischgruppe t2 on t.fk_pk_tischgrp_id = t2.pk_tischgrp_id
                                        WHERE t2.bezeichnung = :tableGroup');
            $stmt->bindParam(':tableGroup', $tableGroup);
            if ($stmt->execute()) {
                $tableItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $tableItems;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }



    static function updateStatusOfTable($status, $time, $tischnr)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare('UPDATE bestellung SET status = ?, timestamp_bis = ? WHERE fk_pk_tischnr_id = ?');
            $stmt->execute([$status,$time,$tischnr]);
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

}