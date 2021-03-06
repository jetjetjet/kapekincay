<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Exports\ReportExport;
use Illuminate\Http\Request;

use Validator;

use App\Libs\Helpers;
use App\Repositories\ReportRepository;
use Auth;
class ReportController extends Controller
{
	public function index(Request $request)
	{
		$inputs = $request->all();
		$data = new \stdClass;
		$user = ReportRepository::getName();

		$print = $request->input('print');
		if(empty($print)){
			if($inputs){
				$total = new \stdClass;
				$data = ReportRepository::grid($inputs);
				$total = ReportRepository::get($inputs);
				// dd($total[0]['total']);	
			}else{
				$total[0]['total'] = '0';	
				$total[1]['totalex'] = '0';
				// dd($total);	
			}
			// dd($total);
			return view('Report.index')->with('data', $data)->with('total', $total)->with('user', $user);
		}

		return (new ReportExport($inputs))->download('Laporan '.$inputs['startdate'].'-'.$inputs['enddate'].'.xlsx');
	}

	public function exIndex(Request $request)
	{
		$inputs = $request->all();
		$data = new \stdClass;

		if($inputs){
			$data = ReportRepository::gridEx($inputs);
			$data->sub = ReportRepository::getEx($inputs);
			
		}else{
			$data->sub['total'] = '0';
		}
		// dd($data);
		return view('Report.exRep')->with('data', $data);
	}

	public function shiftReport(Request $request)
	{
		$inputs = $request->all();
		$data = new \stdClass;
		$user = ReportRepository::getName();
		if($inputs){
			$data = ReportRepository::getShiftReport($inputs);
		}else{
			$data->sub['total'] = '0';
		}
		// dd($data);
		return view('Report.shiftReport')->with('data', $data)->with('user', $user);
	}

	public function menuReport(Request $request)
	{
		$inputs = $request->all();
		// dd($inputs);
		$data = new \stdClass;
		if($inputs){
			$data = ReportRepository::getMenuReport($inputs);
		}else{
			$data = null;
		}
		// dd($data);
		return view('Report.menuRep')->with('data', $data);
	}
}