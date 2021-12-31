<?php

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../tables/DB_Admin_Tables.php';

$tables = DB_Admin_Tables::getTables();


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


$qrCodePdf->SetHeader('Tischcodes||easyGastro');
$qrCodePdf->SetFooter('||{PAGENO}');

$qrCodePdf->WriteHTML('<table>');



for($i = 0; $i < sizeof($tables); $i++) {
    if ($i % 3 === 0) {
        $qrCodePdf->WriteHTML('<tr>');
    }

    $qrCodePdf->WriteHTML(<<<QRCODE
        <td style="border: 1px solid black; text-align: center">
            <img src="qr-code_generator.php?code={$tables[$i]['tischcode']}">
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

$qrCodePdf->Output();
