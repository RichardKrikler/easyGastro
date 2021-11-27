<?php

namespace easyGastro;

class Pages
{
    public static function changePage($input)
    {
        switch ($input) {
            case "Admin":
                header("Location: admin.php");
                break;
            case "Kellner":
                header("Location: kellner.php");
                break;
            case "Küchenmitarbeiter":
                header("Location: kueche.php");
                break;
            default:
                break;
        }
    }

    public static function checkPage($type, $data)
    {
        if (isset($data) && $data['typ'] !== $type) {
            print_r($data);
            Pages::changePage($data['typ']);
        }

        if (!$data) {
            header("Location: index.php");
        }

        if (isset($_POST['logout'])) {
            header('Location: destroySession.php');
        }

        if (time() > $_SESSION['user']['timeout']) {
            header('Location: destroySession.php');
        }
    }
}