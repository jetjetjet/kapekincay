<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

use App\Libs\Helpers;
use App\Libs\Cetak;
use App\Http\Controllers\ShiftController;
use App\Repositories\OrderRepository;
use App\Repositories\MenuRepository;
use App\Repositories\AuditTrailRepository;
use App\Repositories\ShiftRepository;
use App\Repositories\SettingRepository;
// use App\Events\OrderProceed;
// use App\Events\BoardEvent;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
  public function index()
	{
		return view('Order.index');
	}

	
	public function indexBungkus()
	{
		return view('Order.indexBungkus');
	}

	public function indexCustomer(Request $request)
	{
		$respon = Helpers::$responses;
    $menu = MenuRepository::getMenu();
    return view('Order.orderCustomer')->with('menu', $menu);
	}

  public function getGridaway(Request $request)
	{
		$filter = Helpers::getFilter($request);
		$results = OrderRepository::gridTakeAway($filter);
		
		return response()->json($results);
	}

	public function getGridin(Request $request)
	{
		$filter = Helpers::getFilter($request);
		$results = OrderRepository::gridDineIn($filter);
		
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

	public function apiSave(Request $request, $id = null)
	{
    
    $respon = Helpers::$responses;
		$rules = array(
			'ordertype' => 'required'
		);
		
		$inputs = $request->all();
		
		$validator = validator::make($inputs, $rules);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$loginid = Auth::user()->getAuthIdentifier();
    $results = OrderRepository::save($respon, $id, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Buat Pesanan', $loginid);

    if($results['status'] == "success"){
			$data = OrderRepository::getOrderReceipt($results['id']);
			$cetak = Cetak::print($data);
		}
		// return response()->json($results);
	}

  public function save(Request $request, $id = null)
  {
    $respon = Helpers::$responses;
		$rules = array(
			'ordertype' => 'required',
			'orderboardid' => 'required_if:ordertype,DINEIN'
		);
		
		$inputs = $request->all();

		// Subs.
		$inputs['dtl'] = $this->mapRowsX(isset($inputs['dtl']) ? $inputs['dtl'] : null);
		
		$validator = validator::make($inputs, $rules);
		// return redirect()->back()->withInput($inputs);

		if ($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput($inputs);
		}

		$loginid = Auth::user()->getAuthIdentifier();
    $results = OrderRepository::save($respon, $id, $inputs, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Buat Pesanan', $loginid);

    $request->session()->flash($results['status'], $results['messages']);
		
    if($results['status'] == "double"){
      return redirect()->back()->withErrors($results['messages'])->withInput($inputs);
		}

		return redirect('/order/meja/view');
  }

	public function saveCust(Request $request)
	{
    $respon = Helpers::$responses;
		$inputs = $request->all();
		
    $results = OrderRepository::save($respon, $id, $inputs, 2);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Buat Pesanan', 2);
    $request->session()->flash($results['status'], $results['messages']);
		
		return redirect('/order/meja/view');
	}

  public function getDetail(Request $request, $idOrder)
	{
		$results = OrderRepository::GetSubOrder($idOrder);
		
		return response()->json($results);
	}

  public function deliver(Request $request, $id, $idSub){
    $respon = Helpers::$responses;
		$results = OrderRepository::deliver($respon, $id, $idSub, Auth::user()->getAuthIdentifier());
    // event(new OrderProceed('ok'));

		return response()->json($results);
  }

  public function deleteById(Request $request, $id)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = OrderRepository::delete($respon, $id, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Pesanan', $loginid);

		return response()->json($results);
	}

	public function deleteMenuOrder(Request $request, $id, $idSub)
	{
		$respon = Helpers::$responses;

		$loginid = Auth::user()->getAuthIdentifier();
		$results = OrderRepository::deleteMenuOrder($respon, $id, $idSub, $loginid);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Hapus Menu Pesanan', $loginid);

		return response()->json($results);
	}

  public function voidById(Request $request, $id)
	{
		$respon = Helpers::$responses;

    $inputs = $request->all();

		$loginid = Auth::user()->getAuthIdentifier();
		$results = OrderRepository::void($respon, $id, $loginid, $inputs);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Batalkan Pesanan', $loginid);

		// if($results['status'] == "success")
		// 	event(new BoardEvent('ok'));

		return response()->json($results);
	}
  
  public function paidById(Request $request, $id)
	{
		$respon = Helpers::$responses;

    $inputs = $request->all();
	
		$loginid = Auth::user()->getAuthIdentifier();
		$results = OrderRepository::paid($respon, $id, $loginid, $inputs);
		AuditTrailRepository::saveAuditTrail($request->path(), $results, 'Bayar Pesanan', $loginid);

		// if($results['status'] == "success"){
		// 	event(new BoardEvent('ok'));
		// 	event(new OrderProceed('ok'));
		// }
		self::orderReceiptkasir($id, $request);
		$request->session()->flash($results['status'], $results['messages']);

		return redirect('/order/meja/view');
	}

	public function orderReceipt($idOrder)
	{
		$data = OrderRepository::getOrderReceipt($idOrder);
		$cetak = Cetak::print($data);
	}

	public function orderReceiptkasir($id, Request $request)
	{
		$data = OrderRepository::getOrderReceiptkasir($id);
		$inputs = $request->all();
		
		$cetak = Cetak::printkasir($data, $inputs);
		return redirect('/order/meja/view');
	}

	public function opendrawer(Request $request)
	{
		$respon = Helpers::$responses;
		$cetak = Cetak::bukaLaci($respon);

		return response()->json($cetak);
	}

	public function opendraweraudit(Request $request)
	{
		$respon = Helpers::$responses;
		$inputs = $request->all();
		$loginid = Auth::user()->getAuthIdentifier();
		$inputs['pass1'] = SettingRepository::getAppSetting('PasswordLaci');
	
		$cek = Hash::check($inputs['pass'], $inputs['pass1']);

		if($cek == false){
			array_push($respon['messages'], 'Periksa Kembali Password Anda');
			$respon['status'] = "error";
			$respon['errorMessages'] = "Salah Password";
			AuditTrailRepository::saveAuditTrail($request->path(), $respon, 'Buka Laci', $loginid);
			return response()->json($respon);
		}else{
			$cetak = Cetak::bukaLaci($respon);
			AuditTrailRepository::saveAuditTrail($request->path(), $cetak, 'Buka Laci', $loginid);
			return response()->json($cetak);
		}		
	}

	public function ping(Request $request)
	{
		$respon = Helpers::$responses;
		$cetak = Cetak::ping($respon);
		return response()->json($cetak);
	}
}