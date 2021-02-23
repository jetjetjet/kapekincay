<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

use Validator;

use App\Libs\Helpers;
use App\Repositories\SettingRepository;
use App\Repositories\AuditTrailRepository;
use Auth;
class SettingController extends Controller
{
	public function index()
	{
		return view('Setting.index');
	}

	public function getLists(Request $request)
	{
		$perms = Array(
			'save' => (Auth::user()->can(['pengaturan_simpan']) == true ? "true" : "false") . " as can_save"
		);
		$results = SettingRepository::grid($perms);
		
		return response()->json($results);
	}

	public function getById(Request $request, $id = null)
	{
		$respon = Helpers::$responses;
		$results = SettingRepository::get($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([SettingController::class, 'index']);
		}

		return view('Setting.edit')->with('data', $results['data']);
	}

	public function save(Request $request)
	{
		$respon = Helpers::$responses;
		
		$rules = array(
			'settingcategory' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$loginid = Auth::user()->getAuthIdentifier();
		$results = SettingRepository::save($respon, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Ubah Setting', $loginid);
		//cek
		$request->session()->flash($results['status'], $results['messages']);
		return redirect()->action([SettingController::class, 'getById'], ['id' => $results['id']]);
	}

}
