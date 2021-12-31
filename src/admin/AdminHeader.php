<?php

namespace easyGastro\admin;

class AdminHeader
{
    private static array $pages = ['users.php' => 'Benutzer', 'tablegroups.php' => 'Tischgruppen', 'tables.php' => 'Tische', 'drinkgroups.php' => 'Getränkegruppen', 'drinks.php' => 'Getränke', 'quantities.php' => 'Mengen', 'dishgroups.php' => 'Speisegruppen', 'dishes.php' => 'Speisen', 'qr-codes.php' => 'QR-Codes'];

    public static function getNavigation(string $currentPage): string
    {
        $pagesUl = self::getPagesUl($currentPage);
        return <<<NAV
<script src="/admin/admin.js" defer></script>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        $pagesUl
    </div>
  </div>
</nav>
<hr class="my-0">
NAV;
    }

    private static function getPagesUl(string $currentPage): string
    {
        $pagesUl = '<ul class="navbar-nav flex-wrap justify-content-center">';
        foreach (self::$pages as $filename => $pageTitle) {
            $boldClass = $currentPage == $filename ? 'fw-normal text-decoration-underline' : '';
            $url = '/admin/' . $filename;
            $pagesUl .= <<<LI
<li class="nav-item">
    <h4 class="fw-light"><a class="nav-link text-black $boldClass" href="$url">$pageTitle</a></h4>
</li>
LI;
        }
        return $pagesUl . '</ul>';
    }

    public static function getHeader(): string
    {
        return <<<HEADER
<div class="header container-fluid mx-0">
    <div class="row">
        <p class="invisible col"></p>
        <h1 class="fw-normal py-3 fs-3 mb-0 col text-center"><a href="/admin.php" class="text-white text-decoration-none">Adminseite</a></h1>
        <form method="post" class="d-flex flex-column justify-content-center my-auto col">
            <button type="submit" name="logout" id="logoutBt" class="shadow-none bg-unset d-flex flex-column justify-content-center">
                <span class="icon material-icons-outlined mx-2 px-2 text-white text-end">logout</span>
            </button>
        </form>
    </div>
</div>
HEADER;
    }
}