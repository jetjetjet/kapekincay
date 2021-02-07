<?php
namespace App\Repositories;

use App\Models\Order;
use DB;
use Carbon\Carbon;

class OrderRepository
{

  public static function save()
  {

  }

  public static function saveDetailOrder($id, $details, $loginId)
  {
    try{
      foreach ($details as $key=>$dtl){
        if (!isset($dtl->id)){

        } else {

        }
      }
      return true;
    }catch(\Exception $e){
      array_push($result['errorMessages'], $e);
      throw new Exception('rollbacked');
      return false;
    }
  }

  public static function dbOrderHeader($db)
  {
    $ui = new \StdClass();

    $ui->id = $db->id ?? null;
    $ui->orderinvoice = $db->orderinvoice ?? "";
    $ui->orderboardid = $db->orderboardid ?? null;
    $ui->orderboardtext = $db->orderboardtext ?? "";
    $ui->ordercustnametext = $db->odcustname ?? "";
    $ui->ordertype = $db->ordertype ?? "";
    $ui->orderprice = $db->orderprice ?? 0;
    $ui->orderstatus = $db->orderstatus ?? "Draft";
    // $ui->orderpaymentmethod = $db->orderpaymentmethod ?? "Tunai";
    $ui->orderpaid = $db->orderpaid ?? false;
    return $ui;
  }

  public static function generateInvoice()
  {
    $noIvoice = "";
    $q = Order::where('orderactive', '1')
      ->orderBy('ordercreatedat', 'DESC')
      ->select('orderinvoiceindex', DB::raw("extract(day from now()) as tglawal"))
      ->first();
    $cekTgl = $q->tglawal ?? Carbon::now()->format('d');
    if($q == null || $cekTgl == 1){
      $noInvoice = "KPKCO" . Carbon::now()->format('ymd')."001";
    } else {
      $noInvoice = "KPKCO" . Carbon::now()->format('ymd'). ($q->orderinvoiceindex + 1);
    }
    return $noInvoice;
  }
}