<?php
/* Change to the correct path if you copy this example! */
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

try {
    // Enter the share name for your USB printer here
    //$connector = null;
    $logo = EscposImage::load("./loader.png", false);
    $date = date("M. d. Y (l)");
    $connector = new WindowsPrintConnector("POS58 Printer");

    $printer = new Printer($connector);


    /* Print top logo */
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> bitImage($logo);


    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> setTextSize(1, 1);
    $printer -> text("Metro Ormoc Community Multi-\nPurpose Cooperative\n\n");

    $printer -> setTextSize(2, 1);
    $printer -> text("New Accounts\n\n");

    $printer -> setTextSize(8, 8);
    $printer -> text("75\n\n");

    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> setTextSize(1, 1);
    $printer -> text("Date: ".$date);

    $printer -> cut();
    
    /* Close printer */
    $printer -> close();
} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}
