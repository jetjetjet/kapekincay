<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;use Validator;

use App\Libs\Helpers;
use App\Repositories\OrderRepository;
use Auth;

class OrderController extends Controller
{
  public function order()
  {
    return view('Order.pickMenu');
  }

  public function save(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
    
    $inputs = $request->all();
    $rules = array(
			'ordercustname' => 'required'
    );
    dd($inputs);
    $validator = validator::make($inputs, $rules);
		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
    }
    
    $results = OrderRepository::save($request, $inputs, $loginid);
    $request->session()->flash($results['status'], $results['messages']);

		return redirect()->action([OrderController::class, 'detailOrder'], ['id' => $results['id']]);
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
