<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	use HasFactory;
	
  public $timestamps = false;
	protected $fillable = [
    'orderinvoice',
    'orderinvoiceindex',
    'orderboardid',
    'ordertype',
    'ordercustname',
    'orderdate',
    'orderprice',
    'orderstatus',
		'orderdetail',
		'orderpaymentmethod',
		'orderpaid',
		'orderpaidprice',
		'orderpaidremark',
		'orderactive',
		'ordervoid',
		'ordervoidedby',
		'ordervoidedat',
		'ordervoidreason',
		'ordercreatedat',
		'ordercreatedby',
		'ordermodifiedat',
		'ordermodifiedby'
  ];
}
