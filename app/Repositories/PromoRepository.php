<?php
namespace App\Repositories;

use App\Models\Promo;
use App\Models\SubPromo;
use Illuminate\Support\Facades\Log;
use DB;

class PromoRepository
{
  public static function grid($perms)
  {
    return Promo::where('promoactive', '1')
    ->orderBy('promostart', 'DESC')
    ->select(
      'id',
      'promoname',
      'promodetail',
      DB::raw("to_char(promostart, 'dd-mm-yyyy HH24:MI') as promostart"),
      DB::raw("to_char(promoend, 'dd-mm-yyyy HH24:MI') as promoend"),
      'promodiscount',
      DB::raw("case when now()::timestamp without time zone < promoend::timestamp without time zone then 'Aktif' else 'Kadaluarsa' end as promostatus"),
      DB::raw("case when now()::timestamp without time zone < promoend::timestamp without time zone and " .$perms['save'] . " = 1 then true else false end as can_edit"),
      DB::raw("case when now()::timestamp without time zone < promoend::timestamp without time zone and " .$perms['delete'] . " = 1 then true else false end as can_delete")
      )
    ->get();
  }

  public static function get($respon, $id)
  {
    if($id){
      $header = Promo::where('promoactive', '1')
        ->where('promo.id', $id)
        ->select(
          'id',
          'promoname',
          'promodetail',
          DB::raw("to_char(promostart, 'dd-mm-yyyy HH24:MI:SS') as promostart"),
          DB::raw("to_char(promoend, 'dd-mm-yyyy HH24:MI:SS') as promoend"),
          DB::raw("case when now()::timestamp without time zone > promoend::timestamp without time zone then false else true end as editable"),
          'promodiscount')
        ->first();
      if($header == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data tidak ditemukan!');

        return $respon;
      }

      $header->sub = DB::table('subpromo as sp')
        ->join('menus as m', 'm.id', 'spmenuid')
        ->join('menucategory as mc', 'mc.id', 'menumcid')
        ->where('sppromoid', $header->id)
        ->where('spactive', '1')
        ->where('menuactive', '1')
        ->select(
          'sp.id',
          'spmenuid',
          'spindex',
          'menuname',
          'mcname as menucategory',
          'menutype',
          'menuavaible',
          'menuprice')
        ->get();
      
      $respon['data'] = $header;

    } else {
      $data = new \stdClass();
      $respon['data'] = self::getFields($data);
    }
    return $respon;
  }

  public static function save($respon, $inputs, $loginid)
  {
    $respon['success'] = false;
    $id = $inputs['id'] ;
    // $sub = $inputs['sub'];

    try{
      DB::transaction(function () use (&$respon, $id, $inputs, $loginid)
      {
        $valid = self::savePromo($respon, $id, $inputs, $loginid);
        if (!$valid['success']) return $respon;

        if($id != null){
          $valid = self::removeMissingDetails($respon, $id, $inputs['sub'], $loginid);
        }

        $valid = self::saveSubPromo($respon, $id, $inputs['sub'], $loginid);
        if (!$valid['success']) return $respon;

        $respon['status'] = 'success';
      });
    } catch (\Exception $e) {
      $respon['status'] = error;
    }
    return $respon;
  }

  private static function savePromo(&$respon, $id, $inputs, $loginid)
  {
    try{
      $data = "";
      if($id == null){
        $data = Promo::create([
          'promoname' => $inputs['promoname'],
          'promodetail' => $inputs['promodetail'],
          'promostart' => $inputs['promostart'].":00",
          'promoend' => $inputs['promoend'] .":00",
          'promodiscount' => $inputs['promodiscount'],
          'promoactive' => '1',
          'promocreatedat' => now()->toDateTimeString(),
          'promocreatedby' => $loginid
        ]);

        if($data->id != null){
          $respon['id'] = $data->id;
          $respon['success'] = true;
          array_push($respon['messages'], 'Promo berhasil ditambah');
        } else {
          throw new Exception('rollback');
        }
      } else {
        $data = Promo::where('promoactive', '1')
          ->where('id',$id)
          ->update([
            'promoname' => $inputs['promoname'],
            'promodetail' => $inputs['promodetail'],
            'promostart' => $inputs['promostart'],
            'promoend' => $inputs['promoend'],
            'promodiscount' => $inputs['promodiscount'],
            'promomodifiedat' => now()->toDateTimeString(),
            'promomodifiedby' => $loginid]);
            
        $respon['id'] = $id;
        $respon['success'] = true;
        array_push($respon['messages'], 'Promo berhasil diubah.');
      }
    } catch (\Exception $e) {
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("PromoHdrSave_" .trim($eMsg));
      throw new Exception('rollbacked');
    }
    return $respon;
  }

  private static function removeMissingDetails(&$respon, $id, $details, $loginid)
  {
    $ids = Array();
    foreach($details as $dt){
      array_push($ids,$dt['id'] != null ? $dt['id'] :0);
    }
    try{
      $data = SubPromo::where('spactive', '1')
        ->where('sppromoid', $id)
        ->whereNotIn('id', $ids)
        ->update([
          'spactive' => '0',
          'spmodifiedby' => $loginid,
          'spmodifiedat' => now()->toDateTimeString()
          ]);
      $respon['success'] = true;
    } catch(Exception $e){
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("DeleteSubPromo_" . trim($eMsg));
      throw new Exception('rollbacked');
    }
    return $respon;
  }

  private static function saveSubPromo(&$respon, $id, $subs, $loginid)
  {
    $idHeader = $id != null ? $id : $respon['id'];
    $detRow = "";
    try{
      foreach ($subs as $key=>$sub){
        if (!isset($sub['id'])){
          $detRow = SubPromo::create([
            'sppromoid' => $idHeader,
            'spmenuid' => $sub['spmenuid'],
            'spindex' => $key,
            'spactive' => '1',
            'spcreatedat' => now()->toDateTimeString(),
            'spcreatedby' => $loginid
          ]);
        } else {
          $detRow = SubPromo::where('spactive', '1')
            ->where('id', $sub['id'])
            ->update([
              'spmenuid' => $sub['spmenuid'],
              'spindex' => $key,
              'spmodifiedat' => now()->toDateTimeString(),
              'spmodifiedby' => $loginid]);
        }
      }

      $respon['success'] = true;
    }catch(\Exception $e){
      $eMsg = $e->getMessage() ?? "NOT_RECORDED";
      Log::channel('errorKape')->error("SubPromoSave_" . trim($eMsg));
      throw new Exception('rollbacked');
      $respon['success'] = false;
    }
    return $respon;
  }

  public static function delete($respon, $id, $loginid)
  {
    $data = Promo::where('promoactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'promoactive' => '0',
        'promomodifiedby' => $loginid,
        'promomodifiedat' => now()->toDateTimeString()
      ]);

      $detRow = SubPromo::where('spactive', '1')
        ->where('sppromoid', $id)
        ->update([
          'spactive' => '0',
          'spmodifiedat' => now()->toDateTimeString(),
          'spmodifiedby' => $loginid]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Promo Berhasil Dihapus.') 
      : array_push($respon['messages'], 'Promo Tidak Ditemukan');
    
    return $respon;
  }

  public static function getFields($db)
  {
    $db->id = null;
    $db->menucategory = null;
    $db->menuname = null;
    $db->menutype = null;
    $db->menuimg = null;
    $db->menuprice = null;
    $db->promoprice = null;
    $db->menuavaible = null;
    $db->promoname = null;
    $db->promodetail = null;
    $db->promostart = null;
    $db->promoend = null;
    $db->promodiscount = null;
    $db->editable = true;

    $db->sub = Array();

    return $db;
  }
}