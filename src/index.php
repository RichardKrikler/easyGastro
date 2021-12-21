<?php

namespace easyGastro;

require_once 'SiteTemplate.php';
require_once 'Pages.php';
require_once 'db.php';
require_once 'DB_User.php';

session_start();

if (isset($_SESSION['user'])) {
    Pages::changePage($_SESSION['user']['typ']);
}

$loginErrorMsg = '';
if (isset($_POST['submit'])) {
    $stmt = DB_User::getUserForLogin();
    $count = $stmt->rowCount();
    if ($count == 1) {
        $row = $stmt->fetch();
        if (password_verify($_POST['password'], $row['passwort'])) {
            $user = [
                'id' => $row['pk_user_id'],
                'name' => $_POST['username'],
                'password' => $row['passwort'],
                'typ' => $row['typ'],
                'timeout' => (time() + 86400), //86400 seconds = 1 day
            ];
            $_SESSION['user'] = $user;
            Pages::changePage($row['typ']);
        } else {
            $loginErrorMsg = 'Anmeldung fehlgeschlagen! Das falsche Passwort wurde verwendet.';
        }
    } else {
        $loginErrorMsg = 'Anmeldung fehlgeschlagen! Diesen Benutzernamen gibt es nicht.';
    }
}


$nav = <<<NAV
<div class="header text-center">
    <h1 class="text-white fw-normal py-3 fs-3 mb-0">Anmeldeseite</h1>
</div>
NAV;

$body = <<<BODY
<!-- Login; Element -->
<div class="login container col-11 col-sm-8 col-md-6 col-lg-7 col-xl-5 col-xxl-4 my-3 pt-3 pb-4 px-4 mt-5">
    <form method="post">
        <div class="h1 text-center fw-bold">Login</div>
        <div class="mb-3">
            <label for="loginNameInput" class="form-label ms-2 fs-4 fw-normal">Name:</label>
            <input type="text" class="form-control shadow-sm py-2" id="loginNameInput" name="username" required>
        </div>
        <div class="mb-3">
            <label for="loginPasswordInput" class="form-label ms-2 fs-4 fw-normal">Passwort:</label>
            <input type="password" class="form-control shadow-sm" id="loginPasswordInput" name="password" required>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-dark shadow-sm fs-4 mt-4 px-4 py-2 fw-normal bg-black" name="submit">Anmelden
            </button>
        </div>
    </form>
</div>
<div class="bg-white d-flex justify-content-center w-100 mt-5">
    <span class="fs-5 text-danger text-center">$loginErrorMsg</span>
</div>
BODY;


print(SiteTemplate::render('EGS-Login', $nav, $body));
