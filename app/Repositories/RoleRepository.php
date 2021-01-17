<?php
namespace App\Repositories;

use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\UserRoles;

class RoleRepository
{
  public static function grid()
  {
    return Role::where('roleactive', '1')
      ->select(
        'id',
        'rolename',
        'roledetail')
      ->get();
  }

  public static function get($respon, $id)
  {
    $data = new \stdClass();
    $respon['data'] = self::getFields($data);

    if($id){
      $respon['data'] = Role::where('roleactive', '1')
      ->where('id', $id)
      ->select(
        'id',
        'rolefullname',
        'rolename',
        'rolecontact',
        'roleaddress',
        'rolejoindate')
      ->first();

      if($respon['data'] == null){
        $respon['status'] = 'error';
        array_push($respon['messages'],'Data Jabatan tidak ditemukan!');
      }

      //User Role
      $subs = UserRole::where('uractive', '1')
        ->where('urroleid', $id)->select('uruserid')->get();
      //push userid -> role->userid
      $is = Array();
      foreach($subs as $sub){
        array_push($is, $sub['uruserid']);
      }
      $respon['data']->role_permissions = explode(",",$data->rolepermissions);
      $respon['data']->userid = $is;
    }
    return $respon;
  }

  public function saveRole($respon, $id, $inputs, $loginid)
  {
    $role = null;
    if($id == null){

    } else {
      
    }
  }

  public static function save($respon, $inputs, $loginid)
  {
    dd($inputs);
    $id = $inputs['id'] ?? 0;
    $perm = !empty($inputs['permissions']) ? $inputs['permissions'] : array();
    $inputs['perm'] = implode(",", $perm);

    try{
      DB::transaction(function () use (&$respon, $id, $inputs, $loginId){
        $valid = self::saveRole($result, $id, $inputs, $loginId);
        if (!$valid) return $result;

        if($id != null){
          $valid = self::removeMissingUserRole($result, $id, $inputs, $loginId);
        }

        $valid = self::saveUserRole($result, $id, $inputs, $loginId);
        if (!$valid) return $result;

        $result['success'] = true;
      });
    }catch(\Exception $ex){

    }



    $data = Role::where('roleactive', '1')
      ->where('id', $id)
      ->first();

    try{
      if ($data != null){
        $data = $data->update([
          'rolename' => $inputs['rolename'],
          'roledetail' => $inputs['roledetail'],
          'rolemodifiedat' => now()->toDateTimeString(),
          'rolemodifiedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data Jabatan berhasil diubah.');
        
      } else {
        $data = Role::create([
          'rolename' => $inputs['rolename'],
          'roledetail' => $inputs['roledetail'],
          'roleactive' => '1',
          'rolecreatedat' => now()->toDateTimeString(),
          'rolecreatedby' => $loginid
        ]);

        $respon['status'] = 'success';
        array_push($respon['messages'], 'Data Jabatan berhasil ditambah.');
      }
    } catch(\Exception $e){
      $respon['status'] = 'error';
      array_push($respon['messages'], 'Kesalahan! tidak dapat memproses perintah.');
    }
    $respon['id'] = ($data->id ?? $inputs['id']) ?? null;
    return $respon;
  }

  public static function savePerm($respon, $id, $inputs, $loginid)
  {

  }

  public static function delete($respon, $id, $loginid)
  {
    $data = Role::where('roleactive', '1')
      ->where('id', $id)
      ->first();

    $cekDelete = false;

    if ($data != null){
      $data->update([
        'roleactive' => '0',
        'rolemodifiedby' => $loginid,
        'rolemodifiedat' => now()->toDateTimeString()
      ]);
      
      $cekDelete = true;
    }

    $respon['status'] = $data != null && $cekDelete ? 'success': 'error';
    $data != null && $cekDelete
      ? array_push($respon['messages'], 'Data Jabatan berhasil dihapus.') 
      : array_push($respon['messages'], 'Data Jabatan tidak ditemukan');
    
    return $respon;
  }

  public static function getFields($model)
  {
    $model->id = null;
    $model->rolename = null;
    $model->roledetail = null;
    $model->userid = [];
    $model->rolepermissions = null;

    return $model;
  }
}