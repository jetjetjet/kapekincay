<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\AuditTrailRepository;
use Auth;

use Yajra\DataTables\Facades\DataTables;

class AuditTrailController extends Controller
{
  public function index()
	{
		$data = AuditTrailRepository::get();
		return view('AuditTrail.index');
	}

	public function grid(Request $request)
	{
		$data = AuditTrailRepository::get();
		return DataTables::of($data)
		->orderColumn('name', '-name $1')
			->make(true);
	}
}
