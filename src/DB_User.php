<?php

namespace easyGastro;

use PDO;
use PDOException;

require_once "db.php";

class DB_User
{
    static function getUserForLogin()
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("SELECT * FROM User WHERE name = :user");
            $stmt->bindParam(':user', $_POST['username']);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $stmt;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    static function getDataOfUser()
    {
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("SELECT pk_user_id, name, passwort, typ FROM User WHERE name = :name AND passwort = :password AND typ = :typ");
            $stmt->bindParam(':name', $_SESSION['user']['name']);
            $stmt->bindParam(':password', $_SESSION['user']['password']);
            $stmt->bindParam(':typ', $_SESSION['user']['typ']);
            $stmt->execute();

            if ($stmt->execute()) {
                return $stmt->fetch();
            }

            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}
