<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use DB;

class ReportRepository

{
    public static function grid($inputs)
    {
       $od = Order::where('orderactive', '1')
      ->whereBetween('orderdate',[$inputs['startdate'], $inputs['enddate']])
      ->join('users', 'ordercreatedby', '=', 'users.id');
      if($inputs['user'] != 'Semua'){
        $od->where('username', $inputs['user']);
      }
      $data = $od->select(
        'orders.id',
        DB::raw("to_char(orderdate, 'DD-MM-YYYY') as tanggal"),       
        DB::raw("CASE WHEN orders.ordertype = 'DINEIN' THEN 'Makan ditempat' ELSE 'Bungkus' END as ordertypetext"), 
        'orderinvoice',
        'orderprice',
        DB::raw("CASE WHEN orders.orderstatus = 'PAID' THEN 'Lunas' ELSE 'Diproses' END as orderstatuscase"),
        'username',
        )
      ->get();


        
      return $data;
    }
  
    public static function get($inputs)
    {
      $od = Order::where('orderactive', '1')
      ->whereBetween('orderdate',[$inputs['startdate'], $inputs['enddate']])
      ->join('users', 'ordercreatedby', '=', 'users.id');
      if($inputs['user'] != 'Semua'){
        $od->where('username', $inputs['user']);
      }
      $data = $od->select(DB::raw("sum(orderprice) as total"))
        ->first();
        return $data;
    }

    public static function getName()
    {
      return User::where('useractive', '1')
      ->select('username')
        ->get();

    }

  }