<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Helpers;
use App\Repositories\ShiftRepository;
use Auth;
use Validator;

class ShiftController extends Controller
{
    public function index()
	{
		return view('Shift.index');
	}

	public function getLists(Request $request)
	{
		$results = ShiftRepository::grid();
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = ShiftRepository::get($respon, $id);
		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([ShiftController::class, 'index']);
		}
		// dd($respon);
		return view('Shift.edit')->with('data', $results['data']);
	}

	public function getEdit(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = ShiftRepository::getclosedit($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([ShiftController::class, 'index']);
		}
		
		return view('Shift.edit')->with('data', $results['data']);
	}

	public function getClose(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = ShiftRepository::getclosedit($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([ShiftController::class, 'index']);
		}
		
		return view('Shift.close')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		$rules = array(
			'shiftuserid' => 'required',
			'shiftstartcash' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$results = ShiftRepository::save($respon, $inputs, Auth::user()->getAuthIdentifier());
	
    //cek

		$request->session()->flash($results['status'], $results['messages']);
		$cekRes = $results['status'];
		if ($cekRes == 'success'){
		return view('Shift.Index');
		}elseif($cekRes == 'error'){
		return redirect()->action([ShiftController::class, 'getById'], ['id' => $results['id']]);
		}
	}

	public function edit(Request $request)
	{
		$respon = Helpers::$responses;
		$rules = array(
			'shiftuserid' => 'required',
			'shiftstartcash' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$results = ShiftRepository::save($respon, $inputs, Auth::user()->getAuthIdentifier());
	
    //cek

		$request->session()->flash($results['status'], $results['messages']);
		$cekRes = $results['status'];
		if ($cekRes == 'success'){
		return view('Shift.Index');
		}elseif($cekRes == 'error'){
		return redirect()->action([ShiftController::class, 'getEdit'], ['id' => $results['id']]);
		}
	}

	public function close(Request $request)
	{
		$respon = Helpers::$responses;
		$rules = array(
			'shiftendcash' => 'required'
		);

		$inputs = $request->all();
		
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}
		$results = ShiftRepository::close($respon, $inputs, Auth::user()->getAuthIdentifier());
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		$cekRes = $results['status'];
		if ($cekRes == 'success'){
		return view('Shift.Index');
		}elseif($cekRes == 'error'){
		return redirect()->action([ShiftController::class, 'getClose'], ['id' => $results['id']]);
		}
	}

	public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$results = ShiftRepository::delete($respon, $id, Auth::user()->getAuthIdentifier());
		return response()->json($results);
	}

}
