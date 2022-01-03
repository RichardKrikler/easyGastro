<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

$writer = new PngWriter();

$code = $_GET['code'] ?? "INVALID CODE";

$qrCode = QrCode::create($code)
    ->setEncoding(new Encoding('UTF-8'))
    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
    ->setSize(210)
    ->setMargin(8)
    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin());

try {
    $result = $writer->write($qrCode);
    header('Content-Type: ' . $result->getMimeType());
    print($result->getString());
} catch (Exception $e) {
    print_r($e);
}


