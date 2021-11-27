<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGS-Küche</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
          rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="resources/EGS_Logo_outlined_black_v1.png">
</head>
<body class="d-flex flex-column vh-100">
<div class="header text-center">

    <?php
        require_once 'Pages.php';
        require_once 'DB.php';
        require_once 'DB_User.php';

        session_start();

        $row = DatabaseSelects::getDataOfUser();

        Pages::checkPage('Küchenmitarbeiter', $row);
    ?>

    <!-- Header Element -->
    <div class="header d-flex justify-content-between">
        <p class="invisible"></p>
        <h1 class="text-white fw-normal py-3 fs-3 mb-0">Küchenseite</h1>
        <form method="post">
            <button type="submit" name="logout" style="background-color: #6A6A6A" class="shadow-none mx-1 px-1 my-3">
                <span class="material-icons-outlined" style="color: white">logout</span>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="bg-white d-flex justify-content-between w-100 fixed-bottom">
        <!-- Username Element -->
        <div class="username px-3 py-2">
            <p class="fs-4 mb-0"><?php
                $user = $_SESSION['username'];
                echo $user['name'];?></p>

        </div>

        <!-- Copyright Notice Element -->
        <div class="copyright-notice px-3 py-2">
            <p class="fs-4 mb-0">© easyGastro</p>
        </div>
    </div>
</div>