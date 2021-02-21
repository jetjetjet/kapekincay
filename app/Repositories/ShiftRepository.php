<?php
namespace App\Repositories;

use App\Models\Shift;
use DB;
use Illuminate\Support\Facades\Hash;

class ShiftRepository
{
  public static function grid($perms)
  {
    return Shift::where('shiftactive', '1')
    ->join('users', 'shifts.shiftuserid', '=', 'users.id')
    ->select('shifts.id','username','shiftindex', DB::raw("to_char(shiftstart, 'DD-MM-YYYY') as shiftdate"), DB::raw("to_char(shiftstart, 'HH24:MI:SS') as shiftsttime"), DB::raw("to_char(shiftclose, 'HH24:MI:SS') as shiftcltime"), DB::raw('left(shiftenddetail, 15) as shiftenddetail'), DB::raw($perms['save']), DB::raw($perms['delete']), DB::raw($perms['close']), DB::raw($perms['view']))
    ->orderBy('shiftstart', 'DESC')
    ->orderBy('shiftindex', 'ASC')
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
       array_push($respon['messages'], 'Tidak dapat membuat Shift. Masih ada shift yang aktif!');
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
      array_push($respon['messages'],'Shift sudah ditutup tidak bisa diedit');
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
        array_push($respon['messages'], 'Shift berhasil diperbarui');
        
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
        array_push($respon['messages'], 'Shift berhasil ditambah');
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
        array_push($respon['messages'], 'Shift berhasil ditutup');

    } catch(\Exception $e){
      dd($e);
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Error');
    }
    $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
    return $respon;
  }

  public static function delete($respon, $id, $loginid, $inputs)
  {
    $data = Shift::where('shiftactive', '1')
      ->where('id', $id)
      ->where('shiftclose', null)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'shiftdeleteremark' => $inputs['shiftdeleteremark'],
        'shiftactive' => '0',
        'shiftmodifiedby' => $loginid,
        'shiftmodifiedat' => now()->toDateTimeString()
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Shift Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Shift Sudah Ditutup');
    
    return $respon;
  }

  public static function cekShiftStatus()
  {
    $cekShift = Shift::where('shiftactive',1)
      ->whereRaw("( shiftstart::date = now()::date and shiftstart is not null )")
      ->whereNull('shiftclose')
      ->select('id')
      ->first();
    if($cekShift == null)
      return false;
    
    return true;
  }

  public static function shiftDashboard($loginid)
  {
    $text = Array('status'=> 'NEW', 'data' => null);
    $cekShift = Shift::where('shiftactive',1)
      ->whereRaw("( shiftstart::date = now()::date and shiftstart is not null ) and shiftclose is null")
      ->where('shiftcreatedby', $loginid)
      ->select('id', 'shiftstart', 'shiftclose')
      ->first();
    if($cekShift != null){
      $text['data'] = $cekShift;
      $text['status'] = 'AVAILABLE';  
    }
    return $text;
  }

  public static function shiftDashboardActive()
  {
    $cekShift = Shift::join('users', 'users.id', 'shiftcreatedby')
      ->where('shiftactive',1)
      ->whereRaw("( shiftstart::date = now()::date and shiftstart is not null )")
      ->whereNull('shiftclose')
      ->select('shifts.id', 'shiftstart', 'username')
      ->first();
    return $cekShift;
  }

}