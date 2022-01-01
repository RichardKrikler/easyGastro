<?php

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../tables/DB_Admin_Tables.php';


$defaultConfig = (new ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];


$qrCodePdf = new Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/../../resources/Poppins',
    ]),
    'fontdata' => $fontData + [
            'poppins' => [
                'R' => 'Poppins-Regular.ttf',
                'I' => 'Poppins-Italic.ttf',
            ]
        ],
    'default_font' => 'poppins'
]);

$qrCodePdf->SetTitle('Tischcodes - easyGastro');

$qrCodePdf->SetHeader('Tischcodes||easyGastro');

$date = date('d.m.Y H:i');
$qrCodePdf->SetFooter("{$date}||{PAGENO}/{nb}");

$qrCodePdf->WriteHTML('<table>');


$qrCodeURL = 'http://' . $_SERVER['SERVER_NAME'] . '/kunde.php';


$tables = [];
if (isset($_GET['table'])) {
    foreach ($_GET['table'] as $tableId) {
        $tables[] = DB_Admin_Tables::getTable($tableId);
    }
} else {
    $tables = DB_Admin_Tables::getTables();
}


for($i = 0; $i < sizeof($tables); $i++) {
    if ($i % 3 === 0) {
        $qrCodePdf->WriteHTML('<tr>');
    }

    $qrCodePdf->WriteHTML(<<<QRCODE
        <td style="border: 1px solid black; text-align: center">
            <img src="qr-code_generator.php?code=$qrCodeURL?code={$tables[$i]['tischcode']}">
            <div style="font-size: 15pt; font-weight: bold">Tisch: {$tables[$i]['pk_tischnr_id']}</div>
            <div>Code: {$tables[$i]['tischcode']}</div>
        </td>
QRCODE
    );

    if ($i % 3 === 0) {
        $qrCodePdf->WriteHTML('</tr>');
    }
}

$qrCodePdf->WriteHTML('</table>');

$qrCodePdf->Output('Tischcodes-easyGastro.pdf', 'I');
