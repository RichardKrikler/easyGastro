<?php

namespace easyGastro\admin;

use PDO;
use PDOException;
use easyGastro\DB;

require_once __DIR__ . "/../db.php";


class DB_Admin_Users
{
    static function getUsers()
    {
        $DB = DB::getDB();
        $userAr = '';
        try {
            $stmt = $DB->prepare("SELECT pk_user_id, name, typ FROM User");
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

    static function updateUserName(int $userId, string $name)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE User SET name = :name WHERE pk_user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateUserPassword(int $userId, string $password)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE User SET passwort = :password WHERE pk_user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function updateUserType(int $userId, string $type)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("UPDATE User SET typ = :type WHERE pk_user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':type', $type);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    public static function deleteUser(int $userId)
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("DELETE FROM User WHERE pk_user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }

    }
}