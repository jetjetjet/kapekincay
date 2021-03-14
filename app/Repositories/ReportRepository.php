<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use App\Models\Expense;
use DB;

class ReportRepository
{
  public static function grid($inputs)
  {
    $od = Order::where('orderactive', '1')
      ->whereRaw("orderdate::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'")
      ->join('users', 'ordercreatedby', '=', 'users.id');
    if($inputs['user'] != 'Semua'){
      $od->where('users.id', $inputs['user']);
    }
    if($inputs['status'] == 'Diproses'){
      $od->whereNotIn('orderstatus', ['PAID', 'VOIDED']);
    }elseif($inputs['status'] != 'Semua'){
      $od->where('orderstatus', $inputs['status']);
    }elseif($inputs['status'] == 'Semua'){
      $od->whereNotIn('orderstatus', ['VOIDED']);
    }
    $data = $od->select(
      'orders.id',
      DB::raw("to_char(orderdate, 'DD-MM-YYYY') as tanggal"),       
      DB::raw("CASE WHEN orders.ordertype = 'DINEIN' THEN 'Makan ditempat' ELSE 'Bungkus' END as ordertypetext"), 
      'orderinvoice',
      'orderprice',
      DB::raw("CASE WHEN orders.orderstatus = 'PAID' THEN 'Lunas' WHEN orders.orderstatus = 'VOIDED' THEN 'Dibatalkan' ELSE 'Diproses' END as orderstatuscase"),
      'username',
      )
    ->get();
    return $data;
  }
  
  public static function get($inputs)
  {
    $od = Order::where('orderactive', '1')
      ->whereRaw("orderdate::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'")
      ->join('users', 'ordercreatedby', '=', 'users.id');
    if($inputs['user'] != 'Semua'){
      $od->where('users.id', $inputs['user']);
    }
    if($inputs['status'] == 'Diproses'){
      $od->whereNotIn('orderstatus', ['PAID', 'VOIDED']);
    }elseif($inputs['status'] != 'Semua'){
      $od->where('orderstatus', $inputs['status']);
    }elseif($inputs['status'] == 'Semua'){
      $od->whereNotIn('orderstatus', ['VOIDED']);
    }
    $data = $od->select(DB::raw("sum(orderprice) as total"))->first();

    return $data;
  }

  public static function gridEx($inputs)
  {
    $ex = Expense::where('expenseactive', '1')
    ->join('users as cr', 'cr.id', '=', 'expensecreatedby' )
    ->leftJoin('users as er', 'er.id', '=', 'expenseexecutedby')
    ->whereRaw("expensedate::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'");
    if($inputs['status'] == '0'){
      $ex->where('expenseexecutedby', '0');
    }elseif($inputs['status'] == '1'){
      $ex->whereNotIn('expenseexecutedby', ['0']);
    }
    $data = $ex->select(
          'expenses.id',
          'expensename', 
          'expensedetail', 
          'expenseprice',
          DB::raw("to_char(expensedate, 'DD-MM-YYYY') as tanggal"),
          DB::raw("CASE WHEN expenses.expenseexecutedby = '0' THEN 'Draft' ELSE 'Selesai' END as status"),
          'cr.username as create',
          'er.username as execute',
          DB::raw("to_char(expenseexecutedat, 'DD-MM-YYYY') as tanggalend"),
        )
        ->get();

    return $data;
  }

  public static function getEx($inputs){
    $ex = Expense::where('expenseactive', '1')
      ->whereRaw("expensedate::date between '" . $inputs['startdate'] . "' and '" . $inputs['enddate'] . "'");
      if($inputs['status'] == '0'){
        $ex->where('expenseexecutedby', '0');
      }elseif($inputs['status'] == '1'){
        $ex->whereNotIn('expenseexecutedby', ['0']);
      }
    $data = $ex->select(DB::raw("sum(expenseprice) as total"))->first();

    return $data;
  }

  public static function getName()
  {
    return User::where('useractive', '1')
      ->select('id', 'username')
      ->get();
  }

  public static function getShiftReport($filter)
  {
    $q = DB::table('shifts as s')
      ->join('orders as o', 'orderpaidby', 'shiftcreatedby')
      ->join('users as u', 'u.id','shiftcreatedby')
      ->where('shiftactive', '1')
      ->where('orderactive', '1')
      ->whereRaw("shiftcreatedat::date between '". $filter['startdate'] . "'::date and '" . $filter['enddate'] . "'::date")
      ->groupBy(DB::raw("shiftcreatedby, shiftcreatedat::date, username, orderstatus"))
      ->orderBy('shiftcreatedat', 'DESC');
    
    if($filter['status'] == "PAID"){
      $q = $q->where('orderstatus', 'PAID');
    } else if($filter['status'] == 'INPROG'){
      $q = $q->where('orderstatus', 'ADDITIONAL')
        ->orWhere('orderstatus', 'PROCEED');
    } else if($filter['status'] == "VOIDED"){
      $q = $q->where('orderstatus', 'VOID');
    }

    if($filter['user'] != "ALL"){
      $q = $q->where('shiftcreatedby', $filter['user']);
    }
    $getRow = $q->select(
      'shiftcreatedby',
			'username',
			DB::raw("s.shiftcreatedat::date"),
      DB::raw("sum(shiftstartcash) as kertasawal"),
      DB::raw("sum(shiftstartcoin) as koinawal"),
      DB::raw("(sum(shiftstartcash) + sum(shiftstartcoin)) as totalstart"),
      DB::raw("sum(shiftendcash) as kertasakhir"),
      DB::raw("sum(shiftendcoin) as koinakhir"),
      DB::raw("(sum(shiftendcash) + sum(shiftendcoin)) as totalakhir"),
      DB::raw("((sum(shiftendcash) + sum(shiftendcoin))-(sum(shiftstartcash) + sum(shiftstartcoin))) as selisih"),
      DB::raw("sum(orderprice) as totalorder")
    )->get();

    $data = new \StdClass();
    $data->data = $getRow;
    return $data;
  }

}