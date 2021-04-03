<?php
namespace App\Repositories;

use App\Models\Board;
use DB;

class BoardRepository
{
  public static function grid($perms)
  {
    return Board::where('boardactive', '1')
      ->select(
        'id',
        'boardnumber',
        'boardfloor',
        DB::raw($perms['save']),
        DB::raw($perms['delete']))
      ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = Board::getFields($data);

    if($id){
      $respon['data'] = Board::where('boardactive', '1')
      ->where('id', $id)
      ->select('id', 'boardnumber', 'boardfloor')
      ->first();

      if($respon['data'] == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data tidak ditemukan!');
      }
    }
    return $respon;
  }

  public static function getAvailable($id, $searchQ)
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

      if($searchQ){
        $data = $data->whereRaw("upper(concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor)) like upper('%" . $searchQ ."%')");
      }

      return $data->select('boards.id', 
        DB::raw("concat('Meja No. ', boardnumber , ' - Lantai ', boardfloor) as text"))
    // Board::whereRaw('UPPER(customer_name) LIKE UPPER(\'%'. $cari .'%\')')
      ->get();
  }

  public static function save($respon, $inputs, $loginid)
  {
    $id = $inputs['id'] ?? 0;
    $number = $inputs['boardnumber'];
    $floor = $inputs['boardfloor'];

    $cek = Board::where('boardactive', '1')
      ->where('boardnumber', $number)
      ->where('boardfloor', $floor)->first();
    
    if($cek != null){
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Nomor meja dan lantai sudah ada pada sistem.');
    } else {
      $data = Board::where('boardactive', '1')->where('id', $id)->first();
      try{
        if ($data != null){
          $data = $data->update([
            'boardfloor' => $inputs['boardfloor'],
            // 'boardspace' => $inputs['boardspace'],
            'boardmodifiedat' => now()->toDateTimeString(),
            'boardmodifiedby' => $loginid
          ]);
  
          $respon['status'] = 'success';
          array_push($respon['messages'], 'Data Meja berhasil diubah.');
          
        } else {
          $data = Board::create([
            'boardnumber' => $inputs['boardnumber'],
            'boardfloor' => $inputs['boardfloor'],
            // 'boardspace' => $inputs['boardspace'],
            'boardactive' => '1',
            'boardcreatedat' => now()->toDateTimeString(),
            'boardcreatedby' => $loginid
          ]);
  
          $respon['status'] = 'success';
          array_push($respon['messages'], 'Data Meja baru berhasil ditambahkan.');
        }
      } catch(\Exception $e){
        dd($e);
        $respon['status'] = 'error';
        array_push($respon['messages'], 'Error');
      }
      $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
    }

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