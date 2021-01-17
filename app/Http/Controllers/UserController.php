<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\UserRepository;
use Auth;

class UserController extends Controller
{
  public function index()
	{
		return view('User.index');
	}

	public function getLists(Request $request)
	{
		$results = UserRepository::grid();
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = UserRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([UserController::class, 'index']);
		}

		return view('User.edit')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'Userfloor' => 'required',
			'Usernumber' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$results = UserRepository::save($respon, $inputs, Auth::user()->getAuthIdentifier());
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([UserController::class, 'getById'], ['id' => $results['id']]);
	}

	public function changePassword(Request $request, $id)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'userpassword' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		$results = UserRepository::changepassword($respon, $id, $inputs, Auth::user()->getAuthIdentifier());
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([UserController::class, 'getById'], ['id' => $results['id']]);
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$results = UserRepository::delete($respon, $id, Auth::user()->getAuthIdentifier());
		return response()->json($results);
	}
}
