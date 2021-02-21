<?php
namespace App\Repositories;

use App\Models\AuditTrail;
use DB;

class AuditTrailRepository
{
  public static function saveAuditTrail($path, $result, $action, $loginid)
  {
    try{
     $aut = AuditTrail::create([
        'path' => $path,
        'action' => $action,
        'status' => $result['status'],
        'messages' => $result['errorMessages'] ?? null,
        'created_by' => $loginid,
        'created_at' => DB::raw("now()")
      ]);
    } catch(Exception $e)
    {
      //lewaaat
    }
  }
}