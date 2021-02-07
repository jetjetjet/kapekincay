<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\MenuRepository;
use Auth;

class MenuController extends Controller
{
	public function index()
	{
		return view('Menu.index');
	}

	public function getLists(Request $request)
	{
		$results = MenuRepository::grid();
		
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
			$inputs['menuimg']->move(public_path('images'), $imageName);
		 $inputs['menuimgpath'] = '/images/'.$imageName;
		} elseif($request['delimg'] == '1'){
			$inputs['menuimgpath'] = null ;
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
}
