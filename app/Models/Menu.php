<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Menu extends Model
{
  public $timestamps = false;
	protected $fillable = [
    'menuname',
    'menutype',
    'menuimg',
    'menudetail',
    'menuprice',
    'menuactive',
    'menucreatedat',
    'menucreatedby',
    'menumodifiedat',
    'menumodifiedby'
  ];
}
