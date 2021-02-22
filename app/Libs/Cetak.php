<?php
namespace App\Libs;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\CapabilityProfile;

class Cetak
{
  public static function print($data)
  {
    $profile = CapabilityProfile::load("simple");
		$connector = new RawbtPrintConnector();
		$printer = new Printer($connector, $profile);
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer->text("KapeKincay.\n");
    $printer->selectPrintMode();
    $printer->text("Sungai Penuh - Jambi.\n");
    $printer->feed();
    /* Title of receipt */
    $printer->setEmphasis(true);
    $printer->text("Daftar Pesanan\n");
    $printer->text($data->invoice . "\n");
    $printer->text($data->orderType . " - No.". $data->noTable . "\n");
    $printer->setEmphasis(false);
    $printer->feed(1);

    // Body
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->setEmphasis(true);
    // $printer->text(self::getAsString("", $data->price, "Rp "));
    $printer->setEmphasis(false);
    if($data->detail){
      foreach($data->detail as $item){
        $printer->text(self::getAsString($item->qty . " - " . $item->text, $item->totalPrice)); // for 58mm Font A
      }
    }

    $printer->text("--------------------------------\n");
    /* Total */
    $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer->text(self::getAsString("Total ", $data->price, "Rp "));
    $printer->selectPrintMode();

    /* Footer */
    $printer->feed(1);
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("-= Terima Kasih =-\n");
    $printer->feed(1);
		$printer->close();
    // $printer->text($date . "\n");
  }

  public static function getAsString($name, $price, $currency = false)
  {
    $rightCols = 10;
    $width = 32;
    $leftCols = $width - $rightCols;
    if ($currency) {
        $leftCols = $leftCols / 2 - $rightCols / 2;
    }
    $left = str_pad($name, $leftCols);

    $sign = ($currency ? 'Rp ' : '');
    $right = str_pad($sign . $price, $rightCols, ' ', STR_PAD_LEFT);
    return "$left$right\n";
  }
}