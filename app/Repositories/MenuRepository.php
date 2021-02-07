<?php
namespace App\Repositories;

use App\Models\Menu;
use DB;

class MenuRepository

{
    public static function grid()
    {
      return Menu::where('menuactive', '1')->select('id','menuname', 'menutype', 'menuprice')->get();
    }
  
    public static function get($respon, $id)
    {
      $data = new \stdClass();
      $respon['data'] = self::getFields($data);
      $getId = Menu::select('id')->orderBy('id', 'DESC')->first();
      $dId = $getId->id??null;
      $respon['data']->getId = $dId + '1';
      if($id){
        $respon['data'] = Menu::where('menuactive', '1')
        ->where('id', $id)
        ->select('id', 'menuname', 'menutype', 'menuprice','menudetail','menuimg','menuavaible')
        ->first();

  
        if($respon['data'] == null){
          $respon['status'] = 'error';
          array_push($respon['messages'],'Data tidak ditemukan!');
        }
      }
      // dd($respon);
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

    public static function menuapi($respon)
    {
      $tempdata = Array('Makanan'=>Array(), 'Minuman'=>Array(), 'Paket'=>Array());
      $getCat = Menu::where('menuactive', '1')
      ->select('menuname', 'menuimg', 'menuprice', 'menuavaible', 'menutype')
      ->get();
      foreach($getCat as $data )
      {
        if($data->menutype == 'Makanan'){
        array_push($tempdata['Makanan'], $data);
        }else if($data->menutype == 'Minuman'){
          array_push($tempdata['Minuman'], $data);
        }else if($data->menutype == 'Paket'){
          array_push($tempdata['Paket'], $data);
        }
      }
      $respon['status'] = 'success';
      $respon['data'] = $tempdata;
      return $respon;

    }

    public static function getMenu()
    {
      $tempdata = Array('Makanan'=>Array(), 'Minuman'=>Array(), 'Paket'=>Array());
      $getCat = Menu::where('menuactive', '1')
      ->select('id','menuname', 'menuimg', 'menuprice', 'menuavaible', 'menutype')
      ->get();
      foreach($getCat as $data )
      {
        if($data->menutype == 'Makanan'){
        array_push($tempdata['Makanan'], $data);
        }else if($data->menutype == 'Minuman'){
          array_push($tempdata['Minuman'], $data);
        }else if($data->menutype == 'Paket'){
          array_push($tempdata['Paket'], $data);
        }
      }
      return $tempdata;
    }

    public static function getFields($model)
    {
      $model->id = null;
      $model->menuname = null;
      $model->menutype = null;
      //$model->userid = [];
      $model->menuprice = null;
      $model->menudetail = null;
      $model->menuimg = null;
      $model->menuavaible= null;

  
      return $model;
    }
  }