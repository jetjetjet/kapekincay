<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;
use DB;
use Carbon\Carbon;
use Exception;

class OrderRepository
{

  public static function getOrder($respon, $id)
  {
    $data = new \StdClass();
    if($id){
      $data = Order::leftJoin('boards', function($q){
        $q->whereRaw('orderboardid = boards.id')
          ->whereRaw("boardactive = '1'");})
        ->where('orderactive', '1')
        ->where('orders.id', $id)
        ->select(
          'orders.id',
          'orderinvoice',
          DB::raw("concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor, ' Kapasitas ', boardspace, ' Orang') as orderboardtext"),
          'orderboardid',
          'ordertype',
          'ordercustname',
          'orderdate',
          'orderprice',
          'orderstatus',
          'orderdetail',
          'orderpaymentmethod'
        )->first();
      $data->subOrder = self::getSubOrder($id);
      // foreach($subs as $sub){
      //   array_push($ta, self::dbOrderDetail($sub));
      // }
      // $data->subOrder = $ta;
    } else {
      $data = self::dbOrderHeader($data);
    }
    return $data;
  }

  public static function getSubOrder($idOrder)
  {
    return OrderDetail::join('menus',function($q){
      $q->whereRaw("menuactive = '1'")
        ->whereRaw("menus.id = odmenuid");})
      ->where('odactive', '1')
      ->where('odorderid', $idOrder)
      ->select(
        'orderdetail.id',
        'odmenuid',
        DB::raw("menuname as odmenutext"),
        'odqty',
        'odprice',
        'odtotalprice',
        DB::raw("CASE WHEN oddelivered = true then 'Sudah Diantar' ELSE 'Sedang Diproses' END as oddelivertext"),
        'oddelivered',
        'odremark',
        'odindex'
        )
      ->get();
  }

  public static function save($respon, $id, $inputs, $loginid)
  {
    $respon['success'] = false;
    $id = $id != null ? $id : $inputs['id'] ;
    $details = $inputs['dtl'];
    try{
      DB::transaction(function () use (&$respon, $id, $inputs, $loginid)
      {
        $valid = self::saveOrder($respon, $id, $inputs, $loginid);
        if (!$valid['success']) return $respon;

        if($id != null){
          $valid = self::removeMissingDetails($respon, $id, $inputs['dtl'], $loginid);
        }

        $valid = self::saveDetailOrder($respon, $id, $inputs['dtl'], $loginid);
        if (!$valid['success']) return $respon;

        $respon['status'] = 'success';
      });
    } catch (\Exception $e) {
      dd('master',$e);
      $respon['status'] = error;
    }
    return $respon;
  }

  public static function saveOrder(&$respon, $id, $inputs, $loginid)
  {
    try{
      $data = "";
      if($id == null){
        $inv = self::generateInvoice();
        $data = Order::create([
          'orderinvoice' => $inv['invoice'],
          'orderinvoiceindex' => $inv['index'],
          'orderboardid' => $inputs['orderboardid'],
          'ordertype' => $inputs['ordertype'],
          'ordercustname' => $inputs['ordercustname'],
          'orderdate' => now()->toDateTimeString(),
          'orderprice' => $inputs['orderprice'] ?? 1,
          'orderstatus' => 'PROCEED',
          'orderdetail' => $inputs['orderdetail'] ?? null,
          'orderpaid' => '0',
          'orderactive' => '1',
          'ordercreatedat' => now()->toDateTimeString(),
          'ordercreatedby' => $loginid,
        ]);
        if($data->id != null){
          $respon['id'] = $data->id;
          $respon['success'] = true;
          array_push($respon['messages'], 'Pesanan sudah ditambahkan dan sedang diproses.');
        } else {
          throw new Exception('rollback');
        }
      } else {
        $data = Order::where('orderactive', '1')
          ->where('id', $id)
          ->update([
            'orderboardid' => $inputs['orderboardid'] ?? null,
            'ordertype' => $inputs['ordertype'],
            'orderprice' => $inputs['orderprice'] ?? 1,
            //'orderstatus' => 'PROCEED',
            'orderdetail' => $inputs['orderdetail'] ?? "",
            'ordermodifiedat' => now()->toDateTimeString(),
            'ordermodifiedby' => $loginid,
          ]);
        $respon['id'] = $id;
        $respon['success'] = true;
        array_push($respon['messages'], 'Pesanan berhasil diubah.');
      }
    } catch (\Exception $e) {
      dd('saveHead', $e);
      throw new Exception('rollbacked');
    }
    return $respon;
  }

  public static function removeMissingDetails(&$respon, $id, $details, $loginid)
  {
    $ids = Array();
    foreach($details as $dt){
      array_push($ids,$dt['id']);
    }
    try{
      $data = OrderDetail::where('odactive', '1')
        ->where('odorderid', $id)
        ->whereNotIn('id', $ids)
        ->update([
          'odactive' => '0',
          'odmodifiedby' => $loginid,
          'odmodifiedat' => now()->toDateTimeString()
          ]);
      $respon['success'] = true;
    } catch(Exception $e){
      dd('delDetail',$e);
      throw new Exception('rollbacked');
    }
    return $respon;
  }
  
  public static function saveDetailOrder($respon, $id, $details, $loginid)
  {
    $idHeader = $id != null ? $id : $respon['id'];
    $detRow = "";
    try{
      foreach ($details as $key=>$dtl){
        if (!isset($dtl['id'])){
          $detRow = OrderDetail::create([
            'odorderid' => $idHeader,
            'odmenuid' => $dtl['odmenuid'],
            'odqty' => $dtl['odqty'],
            'odprice' => $dtl['odprice'],
            'odtotalprice' => ($dtl['odprice'] * $dtl['odqty']),
            'odremark' => $dtl['odremark'],
            'oddelivered' => '0',
            'odindex' => $key,
            'odactive' => '1',
            'odcreatedat' => now()->toDateTimeString(),
            'odcreatedby' => $loginid
          ]);
        } else {
          $detRow = OrderDetail::where('odactive', '1')
            ->where('id', $dtl['id'])
            ->update([
              'odmenuid' => $dtl['odmenuid'],
              'odqty' => $dtl['odqty'],
              'odprice' => $dtl['odprice'],
              'odtotalprice' => ($dtl['odprice'] * $dtl['odqty']),
              'odremark' => $dtl['odremark'],
              'odindex' => $key,
            ]);
        }
      }
      $respon['success'] = true;
    }catch(\Exception $e){
      dd('errDetailSave', $e);
      throw new Exception('rollbacked');
      $respon['success'] = false;
    }
    return $respon;
  }

  public static function dbOrderDetail($db)
  {
    $ui = new \StdClass();
    
    $ui->id = $db->id ?? null;
    $ui->odorderid = $db->odorderid ?? null;
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

  public static function generateInvoice()
  {
    $invoice = Array();
    $q = Order::where('orderactive', '1')
      ->orderBy('ordercreatedat', 'DESC')
      ->select('orderinvoiceindex', DB::raw("extract(day from now()) as tglawal"))
      ->first();
    $cekTgl = $q->tglawal ?? Carbon::now()->format('d');
    if($q == null || $cekTgl == 1){
      $invoice['index'] = 1;
      $invoice['invoice'] = "KPKCO" . Carbon::now()->format('ymd')."001";
    } else {
      $invoice['index'] = $q->orderinvoiceindex + 1;
      $cek = strlen($invoice['index']);
      $incr = "";
      if($cek == 1){
        $incr = "00" . ($invoice['index']);
      } else if($cek == 2){
        $incr = "0" . ($invoice['index']);
      } else {
        $incr = $invoice['index'];
      }
      $invoice['invoice'] = "KPKCO" . Carbon::now()->format('ymd'). $incr ;
    }
    return $invoice;
  }

  public static function deliver($respon, $id, $loginid, $inputs)
  {
    try{
      $data = OrderDetail::whereIn('id', $inputs['idsub'])
      ->where('oddelivered', '0')
      ->where('odactive', '1')
      ->update([
        'oddelivered' => '1',
        'odmodifiedby' => $loginid,
        'odmodifiedat' => now()->toDateTimeString()
      ]);

      $respon['status'] = 'success';
      array_push($respon['messages'], 'Menu sudah diantar');
      
    }catch(\Exception $e){
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan');
    }
    
    return $respon;
  }
}