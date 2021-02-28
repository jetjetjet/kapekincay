<?php
namespace App\Libs;

use Carbon\Carbon;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\CapabilityProfile;

use App\Repositories\SettingRepository;

class Cetak
{
  private static function getSetting()
  {
    return Array(
      "footer" => SettingRepository::getAppSetting('FooterStruk'),
      "header" => SettingRepository::getAppSetting('HeaderStruk'),
      "AppName" => SettingRepository::getAppSetting('AppName'),
      "IpPrinter" => SettingRepository::getAppSetting('IpPrinter'),
      "Telp" => SettingRepository::getAppSetting('Telp'),
      "Alamat" => SettingRepository::getAppSetting('Alamat'),
      "footerkasir" => SettingRepository::getAppSetting('FooterStrukKasir'),
      "headerkasir" => SettingRepository::getAppSetting('HeaderStrukKasir'),
    );
  }

  public static function print($data)
  {
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new RawbtPrintConnector();
      $printer = new Printer($connector, $profile);
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer->setEmphasis(true);
      $printer->text(self::getSetting()['AppName'] ."\n");
      $printer->selectPrintMode();
      $printer->text(self::getSetting()['header']."\n");
      $printer->text(self::getSetting()['Alamat']."\n");
      $printer->text("================================\n");
      /* Title of receipt */
      $printer->text("Daftar Pesanan\n");
      $printer -> setTextSize(2, 1);
      $printer->text($data->invoice . "\n");
      $printer -> setTextSize(1, 1);
      $printer->text($data->orderType . " - No.". $data->noTable . "\n");
      $printer->setEmphasis(false);
  
      $printer->text("--------------------------------\n");
      // Body
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      $printer->setEmphasis(true);
      // $printer->text(self::getAsString("", $data->price, "Rp "));
      $printer->setEmphasis(false);
      if($data->detail){
        foreach($data->detail as $item){
          $printer->text($item->text."\n");
          $printer->text(self::getAsString($item->qty . " x " . number_format($item->price,0), number_format($item->totalPrice,0))); // for 58mm Font A
        }
      }
  
      $printer->text("--------------------------------\n");
      /* Total */
      $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer->text(self::getAsString("Total ", number_format($data->price,0), "Rp "));
      $printer->selectPrintMode();
  
      /* Footer */
      $printer->feed(1);
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text(self::getSetting()['footer'] . "\n");
      $printer->feed();
      $printer->close();
      // $printer->text($date . "\n");
    }catch(\Exception $e){
      $printer = false;
    }
    
  }

  public static function printKasir($data, $inputs)
  {
    
    // dd($jam);
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new NetworkPrintConnector(self::getSetting()['IpPrinter'], 9100);
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
      $printer->text(self::getSetting()['Alamat']."\n");
      $printer->text(self::getSetting()['Telp']."\n");
      $printer->text(self::getSetting()['headerkasir']."\n");
      
      $printer->feed();
      /* Title of receipt */
      $printer->setEmphasis(true);
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      $printer->text("Nomor Pesanan : " . $data->invoice . "\n");
      $printer->text("Kasir         : ". $inputs['username'] . "\n");
      $printer->text("Tipe Pesanan  : " . $data->orderType . "\n");
      if($data->noTable != null){
        $printer->text("Meja - " . $data->noTable . "\n");
      }
      $printer->text("Tanggal       : ". $data->date . "\n");
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
      $printer->text(self::getSetting()['footerkasir']."\n");
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
  }

  public static function bukaLaci($respon)
  {
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new NetworkPrintConnector(self::getSetting()['IpPrinter'], 9100);
      $printer = new Printer($connector, $profile);
      $printer -> pulse();
      $printer->close();
      $respon['status'] = 'success';
    }catch(\Exception $e){
      $printer = false;
      array_push($respon['messages'], 'Periksa Kertas/Koneksi Di Printer');
      $respon['status'] = "error";
    }
    return $respon;
  }

  public static function ping($respon)
  {
    try{
      $profile = CapabilityProfile::load("simple");
      $connector = new NetworkPrintConnector(self::getSetting()['IpPrinter'], 9100);
      $printer = new Printer($connector, $profile);
      $printer->close();
      $respon['status'] = 'success';
    }catch(\Exception $e){
      $printer = false;
      $respon['status'] = "error";
    }
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