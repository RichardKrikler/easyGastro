<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_Tables
{
    static function getTables(): array
    {
        $DB = DB::getDB();
        $tableAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_tischnr_id, tischcode, fk_pk_tischgrp_id FROM Tisch");
            if ($stmt->execute()) {
                $tableAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $tableAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function createTable(string $tableCode, int $tableGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Tisch (tischcode, fk_pk_tischgrp_id) VALUE (:tableCode, :tableGroupId)");
            $stmt->bindParam(':tableCode', $tableCode);
            $stmt->bindParam(':tableGroupId', $tableGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteTable(int $tableId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM Tisch WHERE pk_tischnr_id = :tableId");
            $stmt->bindParam(':tableId', $tableId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateTableCode($tableId, $tableCode)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Tisch SET tischcode = :tischcode WHERE pk_tischnr_id = :tableId");
            $stmt->bindParam(':tableId', $tableId);
            $stmt->bindParam(':tischcode', $tableCode);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateTableGroupId($tableId, $tableGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Tisch SET fk_pk_tischgrp_id = :tableGroupId WHERE pk_tischnr_id = :tableId");
            $stmt->bindParam(':tableId', $tableId);
            $stmt->bindParam(':tableGroupId', $tableGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}
