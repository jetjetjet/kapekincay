<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

use Validator;

use App\Libs\Helpers;
use App\Repositories\UserRepository;
use App\Repositories\ReportRepository;
use Auth;
class ReportController extends Controller
{
	public function index(Request $request)
	{
		$inputs = $request->all();
		$data = new \stdClass;
		$user = ReportRepository::getName();

		if($inputs){
			$data = ReportRepository::grid($inputs);
			$data->sub = ReportRepository::get($inputs);
			
		}else{
			$data->sub['total'] = '0';
		}
		return view('Report.index')->with('data', $data)->with('user', $user);
	}

}