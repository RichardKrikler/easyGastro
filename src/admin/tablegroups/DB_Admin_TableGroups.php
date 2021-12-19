<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_TableGroups
{
    static function getTableGroups(): array
    {
        $DB = DB::getDB();
        $tischGroupAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_tischgrp_id, bezeichnung FROM Tischgruppe");
            if ($stmt->execute()) {
                $tischGroupAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $tischGroupAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function createTableGroup(string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("INSERT INTO Tischgruppe (bezeichnung) VALUE (:name)");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function deleteTableGroup(int $tableGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM Tischgruppe WHERE pk_tischgrp_id = :tableGroupId");
            $stmt->bindParam(':tableGroupId', $tableGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateTableGroup(int $tableGroupId, string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Tischgruppe SET bezeichnung = :name WHERE pk_tischgrp_id = :tableGroupId");
            $stmt->bindParam(':tableGroupId', $tableGroupId);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getWaiters(): array
    {
        $DB = DB::getDB();
        $userAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_user_id, name, fk_pk_tischgrp_id FROM Kellner INNER JOIN User ON pk_fk_pk_user_id = pk_user_id ORDER BY pk_fk_pk_user_id");
            if ($stmt->execute()) {
                $userAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $userAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateWaiterTableGroup($userId, $tableGroupId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE Kellner SET fk_pk_tischgrp_id = :tableGroupId WHERE pk_fk_pk_user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':tableGroupId', $tableGroupId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}