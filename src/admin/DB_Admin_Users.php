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

    static function getUser($userId): array
    {
        $DB = DB::getDB();
        $userAr = [];
        try {
            $stmt = $DB->prepare("SELECT pk_user_id, name, typ FROM User WHERE pk_user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            if ($stmt->execute()) {
                $userAr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $userAr[0];
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
            $user = self::getUser($userId);

            if ($user['id'] === 1) {
                return;
            }

            switch ($user['typ']) {
                case 'Kellner':
                    $stmt = $DB->prepare("DELETE FROM Kellner WHERE pk_fk_pk_user_id = :userId");
                    break;
                case 'Küchenmitarbeiter':
                    $stmt = $DB->prepare("DELETE FROM Kuechenmitarbeiter WHERE pk_fk_pk_user_id = :userId");
                    break;
                case 'Admin':
                    $stmt = $DB->prepare("DELETE FROM Admin WHERE pk_fk_pk_user_id = :userId");
                    break;
            }

            $stmt->bindParam(':userId', $userId);
            $stmt->execute();


            switch ($type) {
                case 'Kellner':
                    $stmt = $DB->prepare("INSERT INTO Kellner (pk_fk_pk_user_id) VALUE (:userId)");
                    break;
                case 'Küchenmitarbeiter':
                    $stmt = $DB->prepare("INSERT INTO Kuechenmitarbeiter (pk_fk_pk_user_id) VALUE (:userId)");
                    break;
                case 'Admin':
                    $stmt = $DB->prepare("INSERT INTO Admin (pk_fk_pk_user_id) VALUE (:userId)");
                    break;
            }

            $stmt->bindParam(':userId', $userId);
            $stmt->execute();


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
            $user = self::getUser($userId);

            if ($user['id'] === 1) {
                return;
            }

            switch ($user['typ']) {
                case 'Kellner':
                    $stmt = $DB->prepare("DELETE FROM Kellner WHERE pk_fk_pk_user_id = :userId");
                    break;
                case 'Küchenmitarbeiter':
                    $stmt = $DB->prepare("DELETE FROM Kuechenmitarbeiter WHERE pk_fk_pk_user_id = :userId");
                    break;
                case 'Admin':
                    $stmt = $DB->prepare("DELETE FROM Admin WHERE pk_fk_pk_user_id = :userId");
                    break;
            }

            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            $stmt = $DB->prepare("DELETE FROM User WHERE pk_user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    public static function createUser(string $name, string $password, string $type)
    {
        $DB = DB::getDB();

        try {
            $stmt = $DB->prepare("INSERT INTO User (name, passwort, typ) VALUE (:name, :password, :type)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':type', $type);
            $stmt->execute();

            switch ($type) {
                case 'Kellner':
                    $stmt = $DB->prepare("INSERT INTO Kellner (pk_fk_pk_user_id) VALUE (:userId)");
                    break;
                case 'Küchenmitarbeiter':
                    $stmt = $DB->prepare("INSERT INTO Kuechenmitarbeiter (pk_fk_pk_user_id) VALUE (:userId)");
                    break;
                case 'Admin':
                    $stmt = $DB->prepare("INSERT INTO Admin (pk_fk_pk_user_id) VALUE (:userId)");
                    break;
            }

            $userId = $DB->lastInsertId();
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}