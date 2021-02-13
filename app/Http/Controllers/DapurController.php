<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Libs\Helpers;
use App\Repositories\OrderRepository;
use Auth;

use App\Models\Order;
use App\Models\OrderDetail;
use DB;

class DapurController extends Controller
{
  public function index()
  {
    return view('Dapur.index');
  }

  public function getLists(Request $request)
  {
    $data = OrderRepository::getDataDapur();

    return response()->json($data);
  }
}
