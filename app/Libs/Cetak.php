<?php
namespace App\Libs;

use Carbon\Carbon;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
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

  public static function printKasir($data, $inputs)
  {
    
    // dd($jam);
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new NetworkPrintConnector("192.168.192.168", 9100);
      $printer = new Printer($connector, $profile);
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      // $printer->text("Cafe&Resto\n");
      // $printer->text("Syifa Donat\n");
      // // // gambar
      $tux = EscposImage::load(public_path('images/asd.png'),true);     
      $printer -> graphics($tux);
      $printer -> feed();
      $printer->selectPrintMode();
      $printer->text("Sungai Penuh - Jambi.\n");
      $printer->feed();
      /* Title of receipt */
      $printer->setEmphasis(true);
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      $printer->text("Nomor Pesanan : " . $data->invoice . "\n");
      $printer->text("Kasir : ". $inputs['username'] . "\n");
      $printer->text("Tipe Pesanan : " . $data->orderType . "\n");
      if($data->noTable != null){
        $printer->text("Meja - " . $data->noTable . "\n");
      }
      $printer->text("Tanggal : ". $data->date . "\n");
      $printer->setEmphasis(false);
      $printer->feed(1);
  
      // Body
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      
      $printer->setEmphasis(true);
      // $printer->text(self::getAsString("", $data->price, "Rp "));
      $printer->setEmphasis(false);
      if($data->detail){
        $printer->text("================================================\n");
        $printer->text(self::getAsStringkasirmenu('Nama Menu','Harga' , "Jumlah", "Total"));
        $printer->text("------------------------------------------------\n");
        foreach($data->detail as $item){
          $printer->text(self::getAsStringkasirmenu($item->text , number_format($item->price), $item->qty, number_format($item->totalPrice))); // for 58mm Font A
        }
      }
  
      $printer->text("================================================\n");
      /* Total */
      $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer->text(self::getAsStringkasirtotal("Total ", number_format($data->price), "Rp "));


        
          $printer->text(self::getAsStringkasirtotal($data->payment , number_format($data->paidprice), "Rp "));
          $kembalian = $data->paidprice - $data->price;
          $printer->text(self::getAsStringkasirtotal("Kembalian ", number_format($kembalian), "Rp "));
      

      $printer->selectPrintMode();
  
      /* Footer */
      $printer->feed(1);
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text("-= Terima Kasih =-\n");
      $printer->feed(1);

      /* Lisence*/ 
      // $printer -> setFont(Printer::FONT_B);
      // $jam = now()->format('d/m/Y h:i:s');
      // $printer->text(self::getAsStringkasirfooter($jam, '@IkhwanKomputer'));
      // $printer -> pulse();


  
      $printer -> cut();
      $printer->close();
// dd($jam);
    }catch(\Exception $e){
      $printer = false;
    }
    
    
    // $printer->text($date . "\n");
  }

  public static function bukaLaci($respon)
  {
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new NetworkPrintConnector("192.168.192.168", 9100);
      $printer = new Printer($connector, $profile);
      $printer -> pulse();
      $printer->close();
      $respon['status'] = 'success';
    }catch(\Exception $e){
      $printer = false;
      $respon['status'] = "error";
    }
    // dd($respon);
    return $respon;
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

  public static function getAsStringkasirmenu($name, $price, $qty, $currency)
  {
    $rightCols = 8;
    $width = 80;
    $leftCols = 20;
    $middle = 8;
    $middle2 = 12;

    $left = str_pad($name, $leftCols);

    $mid = str_pad($qty, $middle,' ', STR_PAD_LEFT);

    $mid2 = str_pad($price, $middle2,' ', STR_PAD_LEFT);

    $right = str_pad($currency, $rightCols, ' ', STR_PAD_LEFT);
    return "$left$mid2$mid$right\n";
  }

  public static function getAsStringkasirtotal($name, $price, $currency)
  {
    $rightCols = 8;
    $width = 80;
    $leftCols = 12;
    $md = 4;
    $left = str_pad($name, $leftCols);

    $sign = str_pad( 'Rp.', $md, ' ', STR_PAD_LEFT);
    $right = str_pad($price, $rightCols, ' ', STR_PAD_LEFT);
    return "$left$sign$right\n";
  }

  public static function getAsStringkasirfooter($jam, $lisen)
  {
    $rightCols = 44;
    $width = 80;
    $leftCols = 20;
    $left = str_pad($jam, $leftCols);

    $right = str_pad($lisen, $rightCols, ' ', STR_PAD_LEFT);
    return "$left$right\n";
  }
}