<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;use Validator;

use App\Libs\Helpers;
use App\Http\Controllers\ShiftController;
use App\Repositories\OrderRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ShiftRepository;
use App\Events\OrderProceed;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
  public function index()
	{
		return view('Order.index');
	}

  public function getGrid(Request $request)
	{
		$results = OrderRepository::grid();
		
		return response()->json($results);
	}

  public function order(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
    $menu = MenuRepository::getMenu();
    $data = OrderRepository::getOrder($respon, $id);

		if($data['status'] == 'error'){
			$request->session()->flash($data['status'], $data['messages']);
			return redirect()->action([OrderController::class, 'orderView']);
		}

    return view('Order.pickMenu')->with('menu', $menu)->with('data', $data['data']);
  }

  public function detail(Request $request, $id)
  {
    $url = $request->path();
    $kasir = Auth::user()->can(['order_pembayaran'],[]);
    if($kasir){
      $cekShift = ShiftRepository::cekShiftStatus();
      if (!$cekShift){
				$request->session()->put('urlintend', (string)$url);
        $request->session()->flash('warning', ['Shift belum diisi. Mohon diisi terlebih dahulu']);
				
        return redirect()->action([ShiftController::class, 'getById']);
      }
    }
    $respon = Helpers::$responses;
    $results = OrderRepository::getOrder($respon, $id);

		if($results['status'] == 'error'){
			$request->session()->flash($results['status'], $results['messages']);
			return redirect()->action([OrderController::class, 'orderView']);
		}

    return view('Order.detail')->with('data', $results['data']);
  }

	public function orderView()
	{
		return view('Order.boardView');
	}

	public function orderViewLists()
	{
		$perms = Array(
			'is_kasir' => (Auth::user()->can(['order_pembayaran']) == true ? "true" : "false") . " as is_kasir",
			'is_pelayan' => (Auth::user()->can(['order_pelayan']) == true ? "true" : "false") . " as is_pelayan"
		);
		$data = OrderRepository::orderGrid($perms);
		return response()->json($data);
	}

	public function orderBungkus()
	{
		$data = OrderRepository::orderBungkus();
		return DataTables::of($data)->make(true);
	}
  public function save(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
    
    $inputs = $request->all();
    $results = OrderRepository::save($respon, $id, $inputs, Auth::user()->getAuthIdentifier());

    if($results['status'] == "success")
      event(new OrderProceed('ok'));

    $request->session()->flash($results['status'], $results['messages']);

		return redirect()->action([OrderController::class, 'order'], ['id' => $results['id']]);
  }

  public function getDetail(Request $request, $idOrder)
	{
		$results = OrderRepository::GetSubOrder($idOrder);
		
		return response()->json($results);
	}

  public function deliver(Request $request, $id, $idSub){
    $respon = Helpers::$responses;
		$results = OrderRepository::deliver($respon, $id, $idSub, Auth::user()->getAuthIdentifier());
    event(new OrderProceed('ok'));

		return response()->json($results);
  }

  public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$results = OrderRepository::delete($respon, $id, Auth::user()->getAuthIdentifier());
		return response()->json($results);
	}

  public function voidById(Request $request, $id)
	{
		$respon = Helpers::$responses;

    $inputs = $request->all();

		$results = OrderRepository::void($respon, $id, Auth::user()->getAuthIdentifier(), $inputs);
		return response()->json($results);
	}
  
  public function paidById(Request $request, $id)
	{
		$respon = Helpers::$responses;

    $inputs = $request->all();

		$results = OrderRepository::paid($respon, $id, Auth::user()->getAuthIdentifier(), $inputs);
		return response()->json($results);
	}
}