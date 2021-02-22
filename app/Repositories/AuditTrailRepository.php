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
        'createdby' => $loginid,
        'createdat' => DB::raw("now()")
      ]);
    } catch(Exception $e)
    {
      //lewaaat
    }
  }
}