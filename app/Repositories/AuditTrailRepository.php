<?php
namespace App\Repositories;

use App\Models\AuditTrail;
use DB;

class AuditTrailRepository
{
  public static function get()
  {
    return Audittrail::join('users as u', 'u.id', 'createdby')
      // ->orderBy('createdat', 'DESC')
      ->select([
        'u.username',
        'path',
        'action',
        'status',
        'messages',
        'createdat']
      );
  }

  public static function saveAuditTrail($path, $result, $action, $loginid)
  {
    try{
     $aut = AuditTrail::create([
        'path' => $path,
        'action' => $action,
        'status' => $result['status'],
        'messages' => $result['messages'][0] ?? null,
        'createdby' => $loginid,
        'createdat' => DB::raw("now()")
      ]);
    } catch(Exception $e)
    {
      //lewaaat
    }
  }
}