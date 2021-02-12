<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\OrderRepository;
use Auth;

use App\Models\Order;
use App\Models\OrderDetail;
use DB;

class DapurController extends Controller
{
  public function index()
  {
    // event(new App\Events\OrderProceed('ok'));
    return view('Dapur.index');
  }

  public function getLists(Request $request)
  {
    $data = self::getDataDapur();

    return response()->json($data);
  }

  public static function getDataDapur()
  {
    $temp = Array();
    $data = Order::where('orderactive', '1')
      ->leftJoin('boards', 'boards.id', 'orderboardid')
      ->where('orderstatus', 'PROCEED')
      ->orderBy('ordercreatedat')
      ->select(
        'orders.id',
        'ordercustname', 
        'orderinvoice',
        DB::raw("concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor) as orderboardtext"),
        DB::raw("case when ordertype = 'DINEIN' then 'Makan Ditempat' else 'Bungkus' end as ordertype"),
        'orderdate')
      ->get();

      foreach($data as $d){
        $orderHeader = self::dbOrderHeader($d);
        $subs = OrderDetail::join('menus', 'menus.id', 'odmenuid')
          ->where('odactive', '1')
          ->where('odorderid', $d->id)
          ->where('oddelivered', '0')
          ->orderBy('odindex')
          ->select(
            DB::raw('menuname as odmenutext'),
            'odqty',
            'odremark'
          )->get();

          foreach($subs as $s){
            $dataSub = self::dbOrderDetail($s);
            array_push($orderHeader->subOrder, $dataSub);
          }
        array_push($temp, $orderHeader);
      }
    return $temp;
  }

  public static function dbOrderDetail($db)
  {
    $ui = new \StdClass();
    
    $ui->id = $db->id ?? null;
    $ui->odorderid = $db->odorderid ?? null;
    $ui->odmenuid = $db->odmenuid ?? null;
    $ui->odmenutext = $db->odmenutext ?? null;
    $ui->odqty = $db->odqty ?? null;
    $ui->odprice = $db->odprice ?? "";
    $ui->odtotalprice = $db->odtotalprice ?? "";
    $ui->odremark = $db->odremark ?? "";
    
    return $ui;
  }
  
  public static function dbOrderHeader($db)
  {
    $ui = new \StdClass();
    
    $ui->id = $db->id ?? null;
    $ui->orderinvoice = $db->orderinvoice ?? null;
    $ui->orderboardid = $db->orderboardid ?? null;
    $ui->orderboardtext = $db->orderboardtext ?? null;
    $ui->orderboardtext = $db->orderboardtext ?? "";
    $ui->ordertype = $db->ordertype ?? "";
    $ui->ordercustname = $db->ordercustname ?? "";
    $ui->orderdate = $db->orderdate ?? null;
    $ui->orderprice = $db->orderprice ?? null;
    $ui->orderstatus = $db->orderstatus ?? null;
    $ui->orderpaymentmethod = $db->orderpaymentmethod ?? null;
    $ui->orderpaid = $db->orderpaid ?? null;
    $ui->orderpaidprice = $db->orderpaidprice ?? null;
    $ui->orderpaidprice = $db->orderpaidprice ?? null;
    $ui->orderpaidremark = $db->orderpaidremark ?? null;
    $ui->ordervoid = $db->ordervoid ?? null;
    $ui->ordervoidedname = $db->ordervoidedname ?? null;
    $ui->ordervoidedat = $db->ordervoidedat ?? null;
    $ui->ordervoidreason = $db->ordervoidreason ?? null;
    $ui->ordercreatedat = $db->ordercreatedat ?? null;
    $ui->ordercreatedname = $db->ordercreatedname ?? null;
    $ui->ordermodifiedat = $db->ordermodifiedat ?? null;
    $ui->ordermodifiedname = $db->ordermodifiedname ?? null;

    $ui->subOrder = Array();

    return $ui;
  }
}
