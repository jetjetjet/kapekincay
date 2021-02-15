<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\MenuRepository;
use Auth;
use Illuminate\Support\Facades\File;
use Image;

class MenuController extends Controller
{
	public function index()
	{
		return view('Menu.index');
	}

	public function getLists(Request $request)
	{
		$permission = Array(
			'save' => (Auth::user()->can(['menu_simpan']) == true ? "true" : "false") . " as can_save",
			'delete' => (Auth::user()->can(['menu_hapus']) == true ? "true" : "false") . " as can_delete"
		);
		$results = MenuRepository::grid($permission);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = MenuRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([MenuController::class, 'index']);
		}

		return view('Menu.edit')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
	// 	if($request['menuimg'] == !null){
	// 	$rules = array(
	// 		'menuname' => 'required',
	// 		'menuimg' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
	// 		'menuprice' => 'required|integer'
	// 	);
	// }else{
		$rules = array(
			'menuname' => 'required',
			'menuprice' => 'required|integer'
		);
	// }
		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}
	
		// $imageName = time().'.'.$request['menuimg']->extension();     
		// $inputs['menuimg']->move(public_path('images'), $imageName);
		// $inputs['menuimgpath']='/images/'.$imageName;
		if($request['id'] == null){
			$idimg = $request['getid'];
		}else{
		$idimg = $request['id'];
		}
	
		if($request['menuimg'] == !null){
			$imageName = $idimg.'.'.$request['menuimg']->extension();
			$img = Image::make($request->file('menuimg')->getRealPath());
			$img->resize(400, 400, function ($constraint) {
					$constraint->aspectRatio();
			})->save(public_path('images').'/'.$imageName);
		  $inputs['menuimgpath'] = '/images/'.$imageName;
		} elseif($request['delimg'] == '1'){
			$inputs['menuimgpath'] = null ;
			$delimgpath = public_path().$request['hidimg'];
			if(File::exists($delimgpath)) {
				File::delete($delimgpath);
			}
		} elseif($request['menuimg'] == null){
			$inputs['menuimgpath'] = $request['hidimg'];
		}
		$results = MenuRepository::save($respon, $inputs, Auth::user()->getAuthIdentifier());
		// dd($inputs);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([MenuController::class, 'getById'], ['id' => $results['id']]);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$results = MenuRepository::delete($respon, $id, Auth::user()->getAuthIdentifier());
		return response()->json($results);
	}

	public function menuOrder(Request $request)
	{
		$respon = Helpers::$responses;

		$results = MenuRepository::menuapi($respon);
		return response()->json($results);
	}
}
