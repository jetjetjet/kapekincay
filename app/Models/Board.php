<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Board extends Model
{
	use HasFactory;
	protected $fillable = [
    'number',
    'floor',
    'space',
    'active',
    'created_at',
    'modified_at',
    'created_by',
    'modified_by'
  ];

  public static function getFields($model){
    $model->id = null;
    $model->floor = null;
    $model->number = null;
    $model->space = null;
    $model->created_at = null;
    $model->created_by = null;
    $model->updated_at = null;
    $model->updated_by = null;

    return $model;
  }
}