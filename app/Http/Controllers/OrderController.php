<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;use Validator;

use App\Libs\Helpers;
use App\Repositories\OrderRepository;
use App\Repositories\MenuRepository;
use Auth;

class OrderController extends Controller
{
  public function order(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
    $menu = MenuRepository::getMenu();
    $data = OrderRepository::getOrder($respon, $id);
    return view('Order.pickMenu')->with('menu', $menu)->with('data', $data);
  }

  public function save(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
    
    $inputs = $request->all();
    $rules = array(
			'ordercustname' => 'required'
    );

    $validator = validator::make($inputs, $rules);
		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
    
    $results = OrderRepository::save($respon, $id, $inputs, Auth::user()->getAuthIdentifier());
    $request->session()->flash($results['status'], $results['messages']);

		return redirect()->action([OrderController::class, 'detail'], ['id' => $results['id']]);
  }

  public function detail(Request $request, $id)
  {
    $respon = Helpers::$responses;
    $results = OrderRepository::getOrder($respon, $id);
    return view('Order.detail')->with('data', $results);
    
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
			return response()->json($results);
		}
		$results = OrderRepository::deliver($respon, $id, Auth::user()->getAuthIdentifier(), $inputs);
		return response()->json($results);
  }
}