<?php

use easyGastro\DB;

require_once __DIR__ . '/../../db.php';

class DB_Admin_DrinkGroups
{

    static function getDrinkGroups(): array
    {
        $DB = DB::getDB();
        $drinkGroupAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_getraenkegrp_id, bezeichnung FROM Getraenkegruppe");
            if ($stmt->execute()) {
                $drinkGroupAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $drinkGroupAr;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}