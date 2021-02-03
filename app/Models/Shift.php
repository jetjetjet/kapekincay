<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    public $timestamps = false;
	protected $fillable = [
        'shiftuserid',
        'shiftstart',
        'shiftindex',
        'shiftclose',
        'shiftstartcash',
        'shiftstartcoin',
        'shiftendcash',
        'shiftendcoin',
        'shiftenddetail',
        'shiftactive',
        'shiftcreatedat',
        'shiftcreatedby',
        'shiftmodifiedat',
        'shiftmodifiedby'
    ];
    public static function getFields($model)
    {
      $model->id = null;
      $model->shiftuserid = null;
      $model->shiftstart = null;
      $model->shiftstartcash = null;
      $model->shiftstartcoin = null;
      $model->shiftendcash = null;
      $model->shiftendcoin = null;
      $model->shiftenddetail = null;
      $model->shiftclose = null;
      $model->shiftindex = null;

  
      return $model;
    }
}
