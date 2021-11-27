<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGS-Login</title>

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
        <h1 class="text-white fw-normal py-3 fs-3 mb-0">Anmeldeseite</h1>
    </div>

    <!-- Login Element -->
    <div class="login container col-11 col-sm-8 col-md-6 col-lg-7 col-xl-5 col-xxl-4 my-3 pt-3 pb-4 px-4 mt-5">
        <form method="post">
            <div class="h1 text-center fw-bold">Login</div>
            <div class="mb-3">
                <label for="loginNameInput" class="form-label ms-2 fs-4 fw-normal">Name:</label>
                <input type="text" class="form-control shadow-sm py-2" id="loginNameInput" name="username">
            </div>
            <div class="mb-3">
                <label for="loginPasswordInput" class="form-label ms-2 fs-4 fw-normal">Passwort:</label>
                <input type="password" class="form-control shadow-sm" id="loginPasswordInput" name="password">
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-dark shadow-sm fs-4 mt-4 px-4 py-2 fw-normal bg-black" name="submit">Anmelden
                </button>
            </div>
        </form>
    </div>

    <?php
    require_once 'Pages.php';
    require_once 'DB.php';
    require_once 'DatabaseSelects.php';

    session_start();

    if(isset($_SESSION['username'])){
        $usertyp = $_SESSION['username'];
        Pages::changePage($usertyp['typ']);
    }

    if(isset($_POST['submit'])){
        $stmt = DatabaseSelects::getUserForLogin();
        $count = $stmt->rowCount();
        if($count == 1){
            $row = $stmt->fetch();
            if(password_verify($_POST['password'],$row['passwort'])){
                $user = [
                    'name' => $_POST['username'],
                    'password' => $row['passwort'],
                    'typ' => $row['typ'],
                    'timeout' => (time()+86400), //86400 seconds = 1 day
                ];
                $_SESSION['username'] = $user;
                Pages::changePage($row['typ']);
            } else {?>

                <div class="bg-white d-flex justify-content-center w-100">
                    <p class="fs-5 text-danger">Anmeldung fehlgeschlagen! Das falsche Passwort wurde verwendet.</p>
                </div>
    <?php
            }
        } else {?>
            <div class="bg-white d-flex justify-content-center w-100">
                <p class="fs-5 text-danger">Anmeldung fehlgeschlagen! Diesen Benutzernamen gibt es nicht.</p>
            </div>
    <?php
        }
    }
    ?>

    <!-- Footer -->
    <div class="bg-white d-flex justify-content-end w-100 fixed-bottom">
        <!-- Copyright Notice Element -->
        <div class="copyright-notice px-3 py-2">
            <p class="fs-4 mb-0">© easyGastro</p>
        </div>
    </div>
</body>