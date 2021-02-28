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

  public static function changeEnvironmentVariable($key,$value)
  {
    $path = base_path('.env');
    if(is_bool(env($key)))
    {
      $old = env($key)? 'true' : 'false';
    }

    if (file_exists($path)) {
      file_put_contents($path, str_replace(
        "$key=".$old, "$key=".$value, file_get_contents($path)
      ));
    }
  }
}