<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Board extends Model
{
  use HasFactory;
  public $timestamps = false;
	protected $fillable = [
    'boardnumber',
    'boardfloor',
    'boardspace',
    'boardactive',
    'boardcreatedat',
    'boardcreatedby',
    'boardmodifiedat',
    'boardmodifiedby'
  ];

  public static function getFields($model){
    $model->id = null;
    $model->boardnumber = null;
    $model->boardfloor = null;
    $model->boardspace = null;
    $model->boardcreatedat = null;
    $model->boardcreatedby = null;
    $model->boardmodifiedat = null;
    $model->boardmodifiedby = null;

    return $model;
  }
}