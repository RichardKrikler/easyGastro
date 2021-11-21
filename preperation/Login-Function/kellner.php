<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGS-Kellner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
          rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="resources/EGS_Logo_outlined_black_v1.png">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="javascript.js" defer></script>
</head>
<body class="d-flex flex-column vh-100">
<div class="header text-center">

    <?php
        require_once 'LoginFunctions.php';
        $database = LoginFunctions::connectDatabase("localhost","egs","root","");
        session_start();

        $stmt = $database->prepare("SELECT * FROM user WHERE name = :user AND passwort = :password AND typ = :typ");
        $stmt->bindParam(':user',$_SESSION['username']['name']);
        $stmt->bindParam(':password', $_SESSION['username']['password']);
        $stmt->bindParam(':typ',$_SESSION['username']['typ']);
        $stmt->execute();
        $row = $stmt->fetch();

        if (isset($row) && $row["typ"] !== "Kellner"){
            LoginFunctions::changePage($row['typ']);
        }

        if(!$row){
            header("Location: index.php");
        }

        if(isset($_POST['logout'])){
            header('Location: destroySession.php');
        }
    ?>

    <!-- Header Element -->
    <div class="header d-flex justify-content-between">
        <p class="invisible"></p>
        <h1 class="text-white fw-normal py-3 fs-3 mb-0">Kellnerseite</h1>
        <form method="post">
            <button  type="submit" name="logout">Logout</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="bg-white d-flex justify-content-between w-100 fixed-bottom">
        <!-- Username Element -->
        <div class="username px-3 py-2">
            <p class="fs-4 mb-0"><?php
                $user = $_SESSION["username"];
                echo $user["name"]; ?></p>
        </div>

        <!-- Copyright Notice Element -->
        <div class="copyright-notice px-3 py-2">
            <p class="fs-4 mb-0">© easyGastro</p>
        </div>
    </div>
</div>
