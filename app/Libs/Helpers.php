<?php
namespace App\Libs;

class Helpers
{
  public static $responses = array( 'state_code' => '', 'status' => '', 'messages' => array(), 'data' => Array());

  public static function prepareFile($inputs, $subFolder)
  {
    $file = new \StdClass;
    try {
      $file = isset($inputs['file']) ? $inputs['file'] : null;
      $file->path = base_path() . $subFolder;
      $file->newName = time()."_".$file->getClientOriginalName();
      $file->originalName = explode('.',$file->getClientOriginalName())[0];
      $file->move($file->path ,$file->newName);
    } catch (Exception $e){
        // supress
    }
    return $file;
  }
}