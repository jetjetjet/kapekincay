<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ShiftRepository;
use Carbon\Carbon;
use Auth;

class DashboardController extends Controller
{
	public function index()
  {
    $data = new \StdClass();
    $data->bulan = Carbon::now()->isoFormat('MMMM');
    $awal = Carbon::now()->startOfMonth();
    $akhir = $awal->copy()->endOfMonth();

    //Chart
    if(Auth::user()->can(['laporan_lihat'])){
      $filter = Array('awal' => $awal->toDateString(), 'akhir' => $akhir->toDateString());
      $range = range($awal->format('d'), $akhir->format('d'));
      $yM = $awal->format('Y-m');
      $data->chart = OrderRepository::orderChart($filter, $range, $yM );
    }
    
    //Shift
    if(Auth::user()->can(['shift_simpan'])){
      $shift = new \StdClass();
      $getShift = ShiftRepository::shiftDashboard(Auth::user()->getAuthIdentifier());
      $getShiftActive = ShiftRepository::shiftDashboardActive();
      
      $shift->data = $getShift;
      $shift->url = '/shift' . ($getShift['status'] == 'NEW' ? '/detail' : "/close/".$getShift['data']->id); 
      $shift->active = $getShiftActive;

      $data->shift = $shift;
    }
    
    //Meja
    $meja  = OrderRepository::orderGrid(null);
    $sumMeja = new \StdClass();
    $sumMeja->kosong = 0;
    $sumMeja->terisi = 0;
    foreach($meja as $m){
      if($m->boardstatus){
        $sumMeja->kosong += 1;
      }else{
        $sumMeja->terisi += 1;
      }
    }
    $sumMeja->total = $sumMeja->terisi +$sumMeja->kosong;
    $data->countMeja = $sumMeja;

    //Menu
    $filterMenu = Array( 
      1 => "orderdate::date between ". $awal->toDateString() . "::date and " . $akhir->toDateString() . "::date"
    );
    $data->topMenu = MenuRepository::topMenu(null);
    return view('Dashboard.index')->with('data', $data);
  }

  public function getChart()
  {
    $data = new \StdClass();
    $data->bulan = Carbon::now()->isoFormat('MMMM');
    $awal = Carbon::now()->startOfMonth();
    $akhir = $awal->copy()->endOfMonth();
    

    return response()->json($chart);
  }
}
