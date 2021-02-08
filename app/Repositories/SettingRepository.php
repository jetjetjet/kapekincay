<?php
namespace App\Repositories;

use App\Models\Setting;
use DB;

class SettingRepository

{
    public static function grid()
    {
      return Setting::where('settingactive', '1')->select('id','settingcategory','settingkey', DB::raw('left(settings.settingvalue, 30) as settingvalue'))->get();
    }
  
    public static function get($respon, $id)
    {
      $data = new \stdClass();
      $respon['data'] = Setting::getFields($data);
      if($id){
        $respon['data'] = Setting::where('settingactive', '1')
        ->where('id', $id)
        ->select('id', 'settingcategory', 'settingkey', 'settingvalue')
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
  
      $data = Setting::where('settingactive', '1')
        ->where('id',$id)
        ->first();
      try{
          $data = $data->update([
            'settingcategory' => $inputs['settingcategory'],
            'settingkey' => $inputs['settingkey'],
            'settingvalue' => $inputs['settingvalue'],
            'settingmodifiedat' => now()->toDateTimeString(),
            'settingmodifiedby' => $loginid
          ]);
  
          $respon['status'] = 'success';
          array_push($respon['messages'], 'Pengaturan berhasil diubah');
      
      } catch(\Exception $e){
        dd($e);
        $respon['status'] = 'error';
        array_push($respon['messages'], 'Error');
      }
      $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
      return $respon;
    }
  
    
  }