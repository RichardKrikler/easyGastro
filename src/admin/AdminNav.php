<?php

namespace easyGastro\admin;

class AdminNav
{
    private static array $pages = ['users.php' => 'Benutzer', 'tablegroups.php' => 'Tischgruppen', 'tables.php' => 'Tische', 'drinkgroups.php' => 'Getränkegruppen', 'drinks.php' => 'Getränke', 'quantities.php' => 'Mengen', 'dishgroups.php' => 'Speisegruppen', 'dishes.php' => 'Speisen', 'qr-codes.php' => 'QR-Codes'];

    public static function getNavigation(string $currentPage): string
    {
        $pagesUl = self::getPagesUl($currentPage);
        return <<<NAV
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        {$pagesUl}
    </div>
  </div>
</nav>
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
    <h4 class="fw-light"><a class="nav-link text-black {$boldClass}" href="$url">$pageTitle</a></h4>
</li>
LI;
        }
        return $pagesUl . '</ul>';
    }
}