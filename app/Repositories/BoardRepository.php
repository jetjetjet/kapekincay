<?php
namespace App\Repositories;

use App\Models\Board;
use DB;

class BoardRepository
{
  public static function grid()
  {
    return Board::where('boardactive', '1')->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = Board::getFields($data);

    if($id){
      $respon['data'] = Board::where('boardactive', '1')
      ->where('id', $id)
      ->select('id', 'boardnumber', 'boardfloor', 'boardspace')
      ->first();

      if($respon['data'] == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data tidak ditemukan!');
      }
    }
    return $respon;
  }

  public static function getAvailable($id)
  {
    $data =  Board::leftJoin('orders', function($q){
      $q->whereRaw("orderactive = '1'")
        ->whereRaw('orderboardid = boards.id');})
      ->where('boardactive', '1')
      ->whereRaw("(orderstatus is null or orderstatus = 'DIBAYAR')")
      ->orderBy('boardfloor', 'asc')
      ->orderBy('boardnumber', 'asc');
      
      if($id)
        $data = $data->where('boards.id', $id);

      return $data->select('boards.id', 
        DB::raw("concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor, ' Kapasitas ', boardspace, ' Orang') as text"))
    // Board::whereRaw('UPPER(customer_name) LIKE UPPER(\'%'. $cari .'%\')')
      ->get();
  }

  public static function save($respon, $inputs, $loginid)
  {
    $id = $inputs['id'] ?? 0;
    $number = $inputs['boardnumber'];

    $data = Board::where('boardactive', '1')
      ->where(function($q) use($id, $number){
        $q->where('id', $id)
          ->orWhere('boardnumber', $number);
     })->first();
    try{
      if ($data != null){
        $data = $data->update([
          'boardfloor' => $inputs['boardfloor'],
          'boardspace' => $inputs['boardspace'],
          'boardmodifiedat' => now()->toDateTimeString(),
          'boardmodifiedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'pesan sukses update');
        
      } else {
        $data = Board::create([
          'boardnumber' => $inputs['boardnumber'],
          'boardfloor' => $inputs['boardfloor'],
          'boardspace' => $inputs['boardspace'],
          'boardactive' => '1',
          'boardcreatedat' => now()->toDateTimeString(),
          'boardcreatedby' => $loginid
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
    $data = Board::where('boardactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'boardactive' => '0',
        'boardmodifiedby' => $loginid,
        'boardmodifiedat' => now()->toDateTimeString()
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