<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;
use DB;
use Carbon\Carbon;
use Exception;

class OrderRepository
{
  private static function orderBoard($filters)
  {
    $qOrder = Order::where('orderactive', '1')
      ->whereNotNull('orderboardid')
      ->whereNull('ordervoid')
      ->orderBy('ordercreatedat', 'DESC')
      ->select('id', 'orderstatus', 'orderboardid', 'orderinvoice');
    
    $order = DB::table(DB::raw("({$qOrder->toSql()}) a"))
      ->select(
        DB::raw("distinct on(a.orderboardid) a.id"),
        'a.orderstatus', 'a.orderboardid', 'a.orderinvoice'
      )->mergeBindings($qOrder->getQuery());
    
    $board = DB::table('boards')
      ->leftJoinSub($order, 'o', function ($join) {
        $join->on('boards.id', '=', 'o.orderboardid');
      })
      ->where('boardactive', '1')
      ->select(
        'o.orderstatus',
        DB::raw("case when o.orderstatus = 'PAID' then true
        when o.orderstatus is null then true else false end as boardstatus"),
        'o.id as orderid',
        'boards.id as boardid',
        'boardfloor',
        'o.orderinvoice',
        'boardnumber');
    if($filters){
      $board = $board->addSelect(
        DB::raw($filters['is_kasir']),
        DB::raw($filters['is_pelayan'])
      );
    }
    return $board;
  }
  public static function orderGrid($filters)
  {
    $qFloor = DB::table('boards')->where('boardactive', '1')->select(DB::raw("max(boardfloor) as maxfloor"))->first();
    $floorMax = $qFloor->maxfloor ?? 0;
    $board = self::orderBoard($filters)->orderBy('boardfloor', 'ASC')->orderBy('boardnumber', 'ASC')->get();

    return $board;
  }

  public static function orderBungkus()
  {
    $dataOrder = Order::where('orderactive','1')
      ->where('ordertype', 'TAKEAWAY')
      ->whereRaw("(orderpaid is null or orderpaid = '0')")
      ->orderBy('ordercreatedat', 'ASC')
      ->select(
        'id',
        'orderinvoice',
        'orderdate',
        'orderprice')
      ->get();

    return $dataOrder;
  }

  public static function orderChart($filter, $range, $month)
  {
    $transaction = Order::select(DB::raw('ordercreatedat::date as date,sum(orderprice) as total'))
      ->where('orderstatus', 'PAID')
      ->whereRaw($filter)
      ->groupBy(DB::raw('ordercreatedat::date'))->get();
      
    $data = new \StdClass();
    $nom = [];
    foreach ($range as $row) {
      $f_date = strlen($row) == 1 ? 0 . $row:$row;
      $date = $month . "-".  $f_date;
      $total = $transaction->firstWhere('date', $date);
      
      array_push($nom,$total ? $total->total:0);
    }
    
    $data->chartTotal = implode("','", $nom);
    $data->chartTgl = implode("','", $range);
    
    return $data;
  }

  public static function grid()
  {
    return Order::where('orderactive', '1')
    ->select(
      'id',
      'orderinvoice', 
      'orderboardid', 
      // 'ordercustname', 
      DB::raw("CASE WHEN orders.ordertype = 'DINEIN' THEN 'Makan ditempat' ELSE 'Bungkus' END as ordertypetext"), 
      'orderdate',
      DB::raw("to_char(orders.orderprice, '999G999G999G999D99')as orderprice"), 
      DB::raw("CASE WHEN orders.orderstatus = 'PROCEED' THEN 'Diproses' WHEN orders.orderstatus = 'COMPLETED' THEN 'Selesai' WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Batal' WHEN orders.orderstatus = 'ADDITIONAL' THEN 'Proses Tambah' END as orderstatuscase")
      ) 
    ->get();
  }

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
          DB::raw("concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor) as orderboardtext"),
          DB::raw("case when ordertype = 'DINEIN' then 'Makan Ditempat' else 'Bungkus' end as ordertypetext"),
          'orderboardid',
          'ordertype',
          'orderdate',
          'orderprice',
          'orderpaid',
          'orderstatus',
          'orderdetail',
          'orderpaymentmethod',
          'ordervoidedat',
          'ordervoidreason',
          'ordervoidedby'
        )->first();
      if($data == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Pesanan tidak ditemukan!');
      } else {
        $data->subOrder = self::getSubOrder($id);
        $cekDelivered = OrderDetail::where('oddelivered', '0')->where('odorderid', $id)->select(DB::raw("CASE WHEN oddelivered = false THEN '1' else '0' END as odstat"))->first();
        $dId = $cekDelivered->odstat??null;
        $data->getstat = $dId;
      
        $respon['status'] = 'success';
        $respon['data'] = $data;
      }
    } else {
      $respon['data'] = self::dbOrderHeader($data);
    }
    return $respon;
  }

  public static function getDataDapur()
  {
    $temp = Array();
    $data = Order::where('orderactive', '1')
      ->leftJoin('boards', 'boards.id', 'orderboardid')
      ->where('orderstatus', 'PROCEED')
      ->orWhere('orderstatus', 'ADDITIONAL')
      ->orderBy('ordercreatedat')
      ->select(
        'orders.id',
        'orderinvoice',
        DB::raw("case when ordertype = 'DINEIN' then concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor) else '' end as orderboardtext"),
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
            'menutype as odmenutype',
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
          // 'ordercustname' => $inputs['ordercustname'],
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
            'orderstatus' => 'ADDITIONAL',
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
      array_push($ids,$dt['id'] != null ? $dt['id'] :0);
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
    $ui->odmenuid = $db->odmenuid ?? null;
    $ui->odmenutext = $db->odmenutext ?? null;
    $ui->odmenutype = $db->odmenutype ?? null;
    $ui->oddelivered = $db->oddelivered ?? false;
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
    $ui->ordertype = $db->ordertype ?? "";
    $ui->ordertypetext = $db->ordertypetext ?? null;
    // $ui->ordercustname = $db->ordercustname ?? "";
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

  public static function deliver($respon, $id, $idSub, $loginid)
  {
    try{
      DB::beginTransaction();
      $data = OrderDetail::where('id', $idSub)
        ->where('odorderid', $id)
        ->where('oddelivered', '0')
        ->where('odactive', '1');

      $upd = $data->update([
        'oddelivered' => '1',
        'odmodifiedby' => $loginid,
        'odmodifiedat' => now()->toDateTimeString()
      ]);

      $cekDelivered = OrderDetail::where('oddelivered', '0')->where('odactive', '1')->where('odorderid', $id)->first();
      if($cekDelivered == null){
        $updH = Order::where('orderactive', '1')
          ->where('id', $id)->first();
        $headerUpdated = $updH->update(['orderstatus' => 'COMPLETED']);
      }

      DB::commit();
      $respon['status'] = 'success';
      array_push($respon['messages'], 'Menu sudah diantar');
    }catch(\Exception $e){
      // DB::rollback();
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan! Tidak dapat memproses.');
    }
    
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    try{
      DB::beginTransaction();
      $data = Order::where('orderactive', '1')
        ->where('id', $id)
        ->first();
      $datasub = OrderDetail::where('odactive', '1')
        ->where('odorderid', $id);
      
      $ceksub = $datasub->where('oddelivered', '1')->first();
      if($ceksub != null)
        throw new Exception('subDelivered');

      $upd = $datasub->update([
        'odactive' => '0',
        'odmodifiedby' => $loginid,
        'odmodifiedat' => now()->toDateTimeString()
      ]);
      
      if ($data != null){
        $data->update([
          'orderactive' => '0',
          'orderstatus' => 'DELETED',
          'ordermodifiedby' => $loginid,
          'ordermodifiedat' => now()->toDateTimeString()
        ]);
      }

      DB::commit();
      $respon['status'] = 'success';
      array_push($respon['messages'], 'Pesanan berhasil dihapus');
    }catch(\Exception $e){
      $ext = "";
      DB::rollback();
      $respon['status'] = 'error';
      if ($e->getMessage() === 'subDelivered') 
        $ext = "Tidak dapat hapus Pesanan yang sudah diantar.";
      array_push($respon['messages'], 'Kesalahan!' . $ext);
    }
 
    return $respon;
  }

  public static function void($respon, $id, $loginid, $inputs)
  {
    $data = Order::where('orderactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;
    if ($data != null){
      $data->update([
        'ordervoidreason' => $inputs['ordervoidreason'] ,
        'orderstatus' => 'VOIDED',
        'ordervoid' => '1',
        'ordermodifiedby' => $loginid,
        'ordermodifiedat' => now()->toDateTimeString(),
        'ordervoidedby' => $loginid,
        'ordervoidedat' => now()->toDateTimeString()
      ]);       
      $cekDelete = true;
      $respon['status'] = 'success';
      array_push($respon['messages'], 'Pesanan Dibatalkan');
    }else{
    $respon['status'] = 'error';
    array_push($respon['messages'], 'Kesalahan');
    }
    
    return $respon;
  }

  public static function paid($respon, $id, $loginid, $inputs)
  {
    $data = Order::where('orderactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;
    if ($data != null){
      $data->update([
        'orderpaymentmethod' => $inputs['orderpaymentmethod'],
        'orderpaidprice' => $inputs['orderpaidprice'],
        'orderstatus' => 'PAID',
        'orderpaid' => '1',
        'ordermodifiedby' => $loginid,
        'ordermodifiedat' => now()->toDateTimeString()
      ]);       
      $cekDelete = true;
      $respon['status'] = 'success';
      array_push($respon['messages'], 'Pesanan Dibayar');
    }else{
    $respon['status'] = 'error';
    array_push($respon['messages'], 'Kesalahan');
    }
    return $respon;
  }
}