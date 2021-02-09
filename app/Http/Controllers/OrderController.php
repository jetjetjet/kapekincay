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

		dd($results);
  }

  public function proceed(Request $request, $id = null)
  {
    $inputs = $request->all();
    $orderHeader = new \StdClass();
    if($inputs['id'] == null){
      $orderHeader = OrderRepository::dbOrderHeader($orderHeader);
      $orderHeader->orderDetails = $inputs['dtl'];
    }
    return view('Order.proceed')->with('data', $orderHeader);
    
  }
}