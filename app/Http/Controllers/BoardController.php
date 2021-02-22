<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\BoardRepository;
use Auth;

class BoardController extends Controller
{
	public function index()
	{
		return view('Board.index');
	}

	public function getLists(Request $request)
	{
		$perms = Array(
			'save' => (Auth::user()->can(['meja_simpan']) == true ? "true" : "false") . " as can_save",
			'delete' => (Auth::user()->can(['meja_hapus']) == true ? "true" : "false") . " as can_delete"
		);
		$results = BoardRepository::grid($perms);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = BoardRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([BoardController::class, 'index']);
		}

		return view('Board.edit')->with('data', $results['data']);
	}

	public function searchAvailable(Request $request, $id = null)
	{
		$data = BoardRepository::getAvailable($id);
		
		return response()->json($data);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'boardfloor' => 'required',
			'boardnumber' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$results = BoardRepository::save($respon, $inputs, Auth::user()->getAuthIdentifier());
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		if($results['status'] == 'error')
			return redirect()->back()->withInput($inputs);

		return redirect()->action([BoardController::class, 'index']);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$results = BoardRepository::delete($respon, $id, Auth::user()->getAuthIdentifier());
		return response()->json($results);
	}
}
