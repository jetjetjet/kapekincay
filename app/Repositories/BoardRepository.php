<?php
namespace App\Repositories;

use App\Models\Board;
use DB;

class BoardRepository
{
  public static function grid()
  {
    return Board::where('active', '1')->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = Board::getFields($data);

    if($id){
      $respon['data'] = Board::where('active', '1')
      ->where('id', $id)
      ->select('id', 'number', 'floor', 'space')
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
    $number = $inputs['number'];

    $data = Board::where('active', '1')
      ->where(function($q) use($id, $number){
        $q->where('id', $id)
          ->orWhere('number', $number);
     })->first();
    try{
      if ($data != null){
        $data = $data->update([
          'floor' => $inputs['floor'],
          'space' => $inputs['space'],
          'active' => '1',
          'updated_at' => now()->toDateTimeString(),
          'updated_by' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'pesan sukses update');
        
      } else {
        $data = Board::create([
          'number' => $inputs['number'],
          'floor' => $inputs['floor'],
          'space' => $inputs['space'],
          'active' => '1',
          'created_at' => now()->toDateTimeString(),
          'created_by' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'pesan sukses tambah');
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
    $data = Board::where('active', '1')
    ->where('id', $id)
    ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'active' => '0',
        'updated_by' => $loginid,
        'updated_at' => now()->toDateTimeString()
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Data Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Data Tidak Ditemukan');
    
    return $respon;
  }
}