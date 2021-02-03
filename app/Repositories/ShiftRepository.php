<?php
namespace App\Repositories;

use App\Models\Shift;
use DB;
use Illuminate\Support\Facades\Hash;

class ShiftRepository

{
  public static function grid()
  {
    return Shift::where('shiftactive', '1')
    ->join('users', 'shifts.shiftuserid', '=', 'users.id')
    ->select('shifts.id','users.username', 'shifts.shiftstart', 'shifts.shiftclose', DB::raw('left(shifts.shiftenddetail, 15) as shiftenddetail'))
    ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass;
    $respon['data'] = Shift::getFields($data);
    $cekId = Shift::where('id', $id)->select('shiftclose')->first();
    $qClosed = $cekId->shiftclose ?? null;

    if($qClosed != null){
      $respon['status'] = 'error';
      array_push($respon['messages'],'Data sudah ditutup tidak bisa diedit');
    }
    elseif($id){
      $respon['data'] = Shift::where('shiftactive', '1')
      ->where('id', $id)
      ->select('id',
      'shiftuserid',
      'shiftstart',
      'shiftclose',
      'shiftenddetail',
      'shiftstartcash',
      'shiftstartcoin',
      'shiftendcash',
      'shiftendcoin',
      'shiftindex'
      )
      ->first();

      if($respon['data'] == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data tidak ditemukan!');
      }
    }
    $cekIndex = Shift::whereRaw('shiftstart::date = now()::date')->where('shiftactive', '1')->select('shiftindex','shiftclose')->orderBy('shiftindex', 'DESC')->first();
    $qIndex = $cekIndex->shiftindex ?? null;
    $qClose = $cekIndex->shiftclose ?? null;
  
    if($qIndex != null && $qClose == null){
      // error! data sudah ada dan belum diclose
       $respon['status'] = 'error';
       array_push($respon['messages'], 'Masih ada shift yang aktif');
    } else if( $qIndex == null) {
      $respon['data']->shiftindex = 1;
    } else {
      $respon['data']->shiftindex = $cekIndex->shiftindex + 1;
    }
// dd($respon);
    return $respon;
  }


  public static function getclosedit($respon, $id)
  {
    $data = new \stdClass;
    $respon['data'] = Shift::getFields($data);
    $cekId = Shift::where('id', $id)->select('shiftclose')->first();
    $qClosed = $cekId->shiftclose ?? null;

    
    if($qClosed != null){
      $respon['status'] = 'error';
      array_push($respon['messages'],'Data sudah ditutup tidak bisa diedit');
    }
    elseif($id){
      $respon['data'] = Shift::where('shiftactive', '1')
      ->where('id', $id)
      ->select('id',
      'shiftuserid',
      'shiftstart',
      'shiftclose',
      'shiftenddetail',
      'shiftstartcash',
      'shiftstartcoin',
      'shiftendcash',
      'shiftendcoin',
      'shiftindex'
      )
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
    $data = Shift::where('shiftactive', '1')
      ->where('id', $id)
      ->where('shiftclose', null)
      ->first();

    try{
      if ($data != null){
        $data = $data->update([
          'shiftstartcash' => $inputs['shiftstartcash'],
          'shiftstartcoin' => $inputs['shiftstartcoin'],
          'shiftmodifiedat' => now()->toDateTimeString(),
          'shiftmodifiedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'pesan sukses update');
        
      } else {
        $data = Shift::create([
          'shiftuserid' => $loginid,
          'shiftstart' => now()->toDateTimeString(),
          'shiftindex' => $inputs['shiftindex'],
          'shiftstartcash' => $inputs['shiftstartcash'],
          'shiftstartcoin' => $inputs['shiftstartcoin'],
          'shiftactive' => '1',
          'shiftcreatedat' => now()->toDateTimeString(),
          'shiftcreatedby' => $loginid
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
  
  
  public static function close($respon, $inputs, $loginid)
  {
    $id = $inputs['id'] ?? 0;
    $data = Shift::where('shiftclose', null)
      ->where('id', $id)
      ->first();
    try{
        $data = $data->update([
          'shiftclose' => now()->toDateTimeString(),
          'shiftendcash' => $inputs['shiftendcash'],
          'shiftendcoin' => $inputs['shiftendcoin'],
          'shiftenddetail' => $inputs['shiftenddetail'],
          'shiftmodifiedat' => now()->toDateTimeString(),
          'shiftmodifiedby' => $loginid
        ]);
        $respon['status'] = 'success';
        array_push($respon['messages'], 'pesan sukses update');

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
    $data = Shift::where('shiftactive', '1')
      ->where('id', $id)
      ->where('shiftclose', null)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'shiftactive' => '0',
        'shiftmodifiedby' => $loginid,
        'shiftmodifiedat' => now()->toDateTimeString()
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Data Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Data Sudah Ditutup');
    
    return $respon;
  }

}