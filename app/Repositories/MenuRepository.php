<?php
namespace App\Repositories;

use App\Models\Menu;
use DB;

class MenuRepository
{
  public static function grid($perms)
  {
    return Menu::where('menuactive', '1')
    ->select('id',
      'menuname', 
      'menutype', 
      DB::raw("to_char(menuprice, '999G999G999G999D99')as menuprice"), 
      DB::raw("CASE WHEN menus.menuavaible = true THEN 'Tersedia' ELSE 'Kosong' END as menuavaible"),
      DB::raw($perms['save']),
      DB::raw($perms['delete']))
    ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = self::getFields($data);

    $getId = Menu::select('id')->orderBy('id', 'DESC')->first();
    $dId = $getId->id??null;
    $respon['data']->getId = $dId + '1';

    if($id){
      $respon['data'] = Menu::join('menucategory as mc', 'mc.id', 'menumcid')
      ->where('mcactive', '1')
      ->where('menuactive', '1')
      ->where('menus.id', $id)
      ->select(
        'menus.id',
        'menumcid',
        'mcname as menumcname',
        'menuname', 
        'menutype', 
        'menuprice',
        'menudetail',
        'menuimg',
        'menuavaible')
      ->first();

      if($respon['data'] == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data tidak ditemukan!');
      }
    }
    return $respon;
  }

  public static function save($respon, $inputs, $loginid)
  {
    $id = $inputs['id'] ?? 0;

    $data = Menu::where('menuactive', '1')
      ->where('id',$id)
      ->first();
    try{
      if ($data != null){
        $data = $data->update([
          'menumcid' => $inputs['menumcid'],
          'menuname' => $inputs['menuname'],
          'menutype' => $inputs['menutype'],
          'menuimg' => $inputs['menuimgpath'],
          'menudetail' => $inputs['menudetail'],
          'menuprice' => $inputs['menuprice'],
          'menuavaible' => $inputs['menuavaible']??'0',
          'menumodifiedat' => now()->toDateTimeString(),
          'menumodifiedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data Menu berhasil diubah');
        
      } else {
        $data = Menu::create([
          'menumcid' => $inputs['menumcid'],
          'menuname' => $inputs['menuname'],
          'menutype' => $inputs['menutype'],
          'menuimg' => $inputs['menuimgpath'],
          'menudetail' => $inputs['menudetail'],
          'menuprice' => $inputs['menuprice'],
          'menuavaible' => $inputs['menuavaible']??'0',
          'menuactive' => '1',
          'menucreatedat' => now()->toDateTimeString(),
          'menucreatedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data Menu berhasil ditambah');
      }
    } catch(\Exception $e){
      dd($e);
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Error');
    }
    $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    $data = Menu::where('menuactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'menuactive' => '0',
        'menumodifiedby' => $loginid,
        'menumodifiedat' => now()->toDateTimeString()
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Menu Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Menu Tidak Ditemukan');
    
    return $respon;
  }

  public static function topMenu($filters)
  {
    $detailOrder = DB::table('orderdetail')
      ->where('odactive', '1')
      ->groupBy('odmenuid')
      ->select(
        DB::raw(" sum(odqty) as totalorder"),
        'odmenuid');
      
    if($filters){
      foreach($filters as $f)
      {
        $detailOrder = $detailOrder->whereRaw($f[0]);
      }
    }

    $data = Menu::joinSub($detailOrder, 'od', function ($join) {
        $join->on('menus.id', '=', 'od.odmenuid');})
      ->select(
        'menuname',
        'menuprice',
        'od.totalorder')
      ->orderBy('od.totalorder', 'DESC')->limit(10)->get();

    return $data;
  }

  public static function menuapi($respon)
  {
    $tempdata = Array('Makanan'=>Array(), 'Minuman'=>Array());
    $getCat = Menu::join('menucategory as mc', 'mc.id', 'menumcid')
      ->where('mcactive', '1')
      ->where('menuactive', '1')
      ->select('menuname', 'menuimg', 'menuprice', 'menuavaible', 'menutype')
      ->get();

    foreach($getCat as $data )
    {
      if($data->menutype == 'Makanan'){
      array_push($tempdata['Makanan'], $data);
      }else if($data->menutype == 'Minuman'){
        array_push($tempdata['Minuman'], $data);
      }
    }
    $respon['status'] = 'success';
    $respon['data'] = $tempdata;

    return $respon;
  }

  public static function getMenu()
  {
    $tempdata = Array('Makanan'=>Array(), 'Minuman'=>Array());
    $getCat = Menu::join('menucategory as mc', 'mc.id', 'menumcid')
      ->where('mcactive', '1')
      ->where('menuactive', '1')
      ->select('menuname', 'menuimg', 'menuprice', 'menuavaible', 'menutype')
      ->get();
      
    foreach($getCat as $data )
    {
      if($data->menutype == 'Makanan'){
      array_push($tempdata['Makanan'], $data);
      }else if($data->menutype == 'Minuman'){
        array_push($tempdata['Minuman'], $data);
      }
    }
    return $tempdata;
  }

  public static function getFields($model)
  {
    $model->id = null;
    $model->menuname = null;
    $model->menutype = null;
    $model->menumcid = null;
    $model->menumcname = null;
    //$model->userid = [];
    $model->menuprice = null;
    $model->menudetail = null;
    $model->menuimg = null;
    $model->menuavaible= null;


    return $model;
  }
}