<?php

class LoginFunctions
{
    function __construct(){

    }

    public static function connectDatabase($host,$dbname,$user,$passwd){
        return new PDO("mysql:host={$host};dbname={$dbname}",$user,$passwd);
    }

    public static function changePage($input){
        switch ($input) {
            case "Admin":
                header("Location: admin.php");
                break;
            case "Kellner":
                header("Location: kellner.php");
                break;
            case "Küchenmitarbeiter":
                header("Location: kuechenmitarbeiter.php");
                break;
            default:
                break;
        }
    }
}