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

    static function getTable(int $tableId): array
    {
        $DB = DB::getDB();
        $tableAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_tischnr_id, tischcode, fk_pk_tischgrp_id FROM Tisch WHERE pk_tischnr_id = :tableId");
            $stmt->bindParam(':tableId', $tableId);
            if ($stmt->execute()) {
                $tableAr = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $tableAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function createTable(int $tableId, int $tableGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Tisch (pk_tischnr_id, tischcode, fk_pk_tischgrp_id) VALUE (:tableId, :tableCode, :tableGroupId)");
            $stmt->bindParam(':tableId', $tableId);
            $tableCode = self::generateTableCode();
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

    static function generateTableCode(): string
    {
        do {
            $tableCode = strtoupper(substr(bin2hex(random_bytes(5)), 0, 5));
        } while (in_array($tableCode, array_column(self::getTables(), 'tischcode')));
        return $tableCode;
    }

    static function updateTableCode(int $tableId, string $tableCode)
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

    static function updateTableGroupId(int $tableId, int $tableGroupId)
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
