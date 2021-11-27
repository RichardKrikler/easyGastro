<?php

require_once "DB.php";

class DatabaseSelects
{
    public static function getUserForLogin(){
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("SELECT * FROM user WHERE name = :user");
            $stmt->bindParam(':user',$_POST['username']);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $stmt;
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }

    public static function getDataOfUser(){
        $DB = DB::getDB();
        try {
            $stmt = $DB->prepare("SELECT * FROM user WHERE name = :user AND passwort = :password AND typ = :typ");
            $stmt->bindParam(':user',$_SESSION['username']['name']);
            $stmt->bindParam(':password', $_SESSION['username']['password']);
            $stmt->bindParam(':typ',$_SESSION['username']['typ']);
            $stmt->execute();
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $stmt->fetch();
        } catch (PDOException  $e) {
            print('Error: ' . $e);
            exit();
        }
    }
}