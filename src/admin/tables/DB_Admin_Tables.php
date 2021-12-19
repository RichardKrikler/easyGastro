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
}