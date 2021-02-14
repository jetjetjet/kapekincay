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
    return view('Order.pickMenu')->with('menu', $menu)->with('data', $data);
  }

  public function detail(Request $request, $id)
  {
    
    $kasir = Auth::user()->can(['order_pembayaran'],[]);
    if($kasir){
      $cekShift = ShiftRepository::cekShiftStatus();
      if (!$cekShift){
        $request->session()->flash('warning', ['Shift belum diisi. Mohon diisi terlebih dahulu']);
        return redirect()->action([ShiftController::class, 'getById']);
      }
    }
    $respon = Helpers::$responses;
    $results = OrderRepository::getOrder($respon, $id);
    return view('Order.detail')->with('data', $results);
  }

	public function orderView()
	{
		return view('Order.boardView');
	}

	public function orderViewLists()
	{
		$data = OrderRepository::orderGrid(null)->get();
		return response()->json($data);
	}

  public function save(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
    
    $inputs = $request->all();
    // $rules = array(
		// 	'ordercustname' => 'required'
    // );

    // $validator = validator::make($inputs, $rules);
		// if ($validator->fails()){
		// 	return redirect()->back()->withErrors($validator)->withInput($inputs);
    // }
    
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

  public function deliver(Request $request, $id){
    $respon = Helpers::$responses;
		$rules = array(
			'idsub' => 'required'
		);

		$inputs = $request->all();
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return response()->json($respon);
		}
		$results = OrderRepository::deliver($respon, $id, Auth::user()->getAuthIdentifier(), $inputs);
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