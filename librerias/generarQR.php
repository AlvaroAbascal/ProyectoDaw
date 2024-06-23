<?php
/**/
require '../vendor/autoload.php';

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;



function crearQr($texto, $empresa) {
    $writer = new PngWriter();
    // Create QR code
    $qrCode = QrCode::create($texto)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
        ->setSize(300)
        ->setMargin(10)
        ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));

    // Create generic logo
    $logo = Logo::create(__DIR__ . '/assets/logo.png')
        ->setResizeToWidth(55)
        ->setPunchoutBackground(true);

    // Create generic label
    $label = Label::create($empresa)
        ->setTextColor(new Color(255, 0, 0));

    $result = $writer->write($qrCode, $logo, $label);

    // Validate the result
    $writer->validateResult($result, $texto);

    return '<img src="data:image/png;base64,' . base64_encode($result->getString()) . '" alt="QR Code">';
}

// Mostrar el código QR en pantalla
//echo '<img src="data:image/png;base64,' . base64_encode($resultadoQR->getString()) . '" alt="QR Code">';