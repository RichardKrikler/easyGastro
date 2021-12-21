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
            $stmt = $DB->prepare('SELECT DISTINCT pk_tischnr_id FROM Tisch
                                        INNER JOIN Tischgruppe t on Tisch.fk_pk_tischgrp_id = t.pk_tischgrp_id
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
            $stmt = $DB->prepare('SELECT bezeichnung FROM Tischgruppe
                                        INNER JOIN Kellner k on Tischgruppe.pk_tischgrp_id = k.fk_pk_tischgrp_id
                                        INNER JOIN User u on k.pk_fk_pk_user_id = u.pk_user_id
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

    static function getTableGroupOfTable($tableID)
    {
        $DB = DB::getDB();
        $tableGroup = '';
        try {
            $stmt = $DB->prepare('SELECT pk_tischgrp_id, bezeichnung
                                        FROM Tischgruppe
                                        INNER JOIN Tisch t on Tischgruppe.pk_tischgrp_id = t.fk_pk_tischgrp_id
                                        WHERE t.pk_tischnr_id = :tischnr');
            $stmt->bindParam(':tischnr', $tableID);
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
                                        INNER JOIN Bestellung b on bestellung_speise.pk_fk_pk_bestellung_id = b.pk_bestellung_id
                                        INNER JOIN Speise s on bestellung_speise.pk_fk_pk_speise = s.pk_speise_id
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
                                        INNER JOIN Bestellung b on bestellung_getraenkmenge.pk_fk_pk_bestellung_id = b.pk_bestellung_id
                                        INNER JOIN Getraenk_Menge gm on bestellung_getraenkmenge.pk_fk_pk_getraenkmg_id = gm.pk_getraenkmg_id
                                        INNER JOIN Getraenk g on gm.fk_pk_getraenk_id = g.pk_getraenk_id
                                        INNER JOIN Menge m on gm.fk_pk_menge_id = m.pk_menge_id
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
                                        FROM Bestellung
                                        INNER JOIN Tisch t on Bestellung.fk_pk_tischnr_id = t.pk_tischnr_id
                                        INNER JOIN Tischgruppe t2 on t.fk_pk_tischgrp_id = t2.pk_tischgrp_id
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
            $stmt = $DB->prepare('UPDATE Bestellung SET status = ?, timestamp_bis = ? WHERE fk_pk_tischnr_id = ?');
            $stmt->execute([$status,$time,$tischnr]);
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

}