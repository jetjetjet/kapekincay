<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MenuCategoryRepository;
use App\Libs\Helpers;
use Auth;

class MenuCategoryController extends Controller
{
  public function save(Request $request)
	{
		$respon = Helpers::$responses;
		$inputs = $request->all();
		$results = MenuCategoryRepository::save($respon, $inputs, Auth::user()->getAuthIdentifier());
		
		return response()->json($results, $results['state_code']);
	}

	public function delete(Request $request, $id)
	{
		$respon = Helpers::$responses;
		$results = MenuCategoryRepository::delete($respon, $id, Auth::user()->getAuthIdentifier());
		
		return response()->json($results, $results['state_code']);
	}

	public function search(Request $request)
  {
    if ($request->has('q')) {
      $cari = $request->q;
      $data = MenuCategoryRepository::search($cari);
      
      return response()->json($data);
    }
  }
}
