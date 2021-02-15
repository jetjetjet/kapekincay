<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\OrderController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('login', [LoginController::class, 'index']);
Route::get('logout', [LoginController::class, 'getLogoff']);
Route::post('login', [LoginController::class, 'postLogin']);
Route::get('/dapur', [App\Http\Controllers\DapurController::class, 'index']);
Route::get('/dapur/lists', [App\Http\Controllers\DapurController::class, 'getLists']);

Route::group(array('middleware' => 'auth'), function ()
{
  Route::get('/', [DashboardController::class, 'index']);
 
  Route::get('/jabatan', [RoleController::class, 'index'])->middleware('can:jabatan_lihat');
  Route::get('/jabatan/grid', [RoleController::class, 'getLists'])->middleware('can:jabatan_lihat');
  Route::get('/jabatan/detail/{id?}', [RoleController::class, 'getById'])->middleware('can:jabatan_lihat', 'can:jabatan_simpan');
  Route::post('/jabatan/simpan', [RoleController::class, 'save'])->middleware('can:jabatan_simpan');
  Route::post('/jabatan/hapus/{id}', [RoleController::class, 'deleteById'])->middleware('can:jabatan_hapus');

  
  Route::get('/meja', [BoardController::class, 'index'])->middleware('can:meja_lihat');
  Route::get('/meja/grid', [BoardController::class, 'getLists'])->middleware('can:meja_lihat');
  Route::get('/meja/detail/{id?}', [BoardController::class, 'getById'])->middleware('can:meja_simpan', 'can:meja_lihat');
  Route::get('/meja/cariTersedia/{id?}', [BoardController::class, 'searchAvailable']);
  Route::post('/meja/simpan', [BoardController::class, 'save'])->middleware('can:meja_simpan');
  Route::post('/meja/hapus/{id}', [BoardController::class, 'deleteById'])->middleware('can:meja_hapus');

  Route::get('/menu', [MenuController::class, 'index'])->middleware('can:menu_lihat');
  Route::get('/menu/grid', [MenuController::class, 'getLists'])->middleware('can:menu_lihat');
  Route::get('/menu/detail/{id?}', [MenuController::class, 'getById'])->middleware('can:menu_lihat', 'can:menu_simpan');
  Route::post('/menu/simpan', [MenuController::class, 'save'])->middleware('can:menu_simpan');
  Route::post('/menu/hapus/{id}', [MenuController::class, 'deleteById'])->middleware('can:menu_hapus');
  Route::get('/menu/menuorder', [ MenuController::class, 'menuOrder']);

  Route::get('/setting', [SettingController::class, 'index'])->middleware('can:pengaturan_lihat');
  Route::get('/setting/grid', [SettingController::class, 'getLists'])->middleware('can:pengaturan_lihat');
  Route::get('/setting/detail/{id?}', [SettingController::class, 'getById'])->middleware('can:pengaturan_edit', 'can:pengaturan_lihat');
  Route::post('/setting/simpan', [SettingController::class, 'save'])->middleware('can:pengaturan_edit');
  
  Route::get('/order/index', [OrderController::class, 'index']);
  Route::get('/order/index/grid', [OrderController::class, 'getGrid']);
  Route::get('/order/{id?}', [OrderController::class, 'order']);
  Route::get('/order/detail/{id?}', [ OrderController::class, 'detail' ]);
  Route::get('/order/detail/grid/{idOrder}', [ OrderController::class, 'getDetail' ]);
  Route::get('/order/meja/view', [OrderController::class, 'orderView']);
  Route::get('/order/meja/lists', [OrderController::class, 'orderViewLists']);
  Route::post('/order/save/{id?}', [OrderController::class, 'save']);
  Route::post('/order/hapus/{id}', [OrderController::class, 'deleteById']);
  Route::post('/order/batal/{id}', [OrderController::class, 'voidById']);
  Route::post('/order/bayar/{id}', [OrderController::class, 'paidById']);
  Route::post('/order/delivered/{id}/{idSub}', [OrderController::class, 'deliver']);

  Route::get('/shift', [ShiftController::class, 'index'])->middleware('can:shift_lihat');
  Route::get('/shift/grid', [ShiftController::class, 'getLists'])->middleware('can:shift_lihat');
  Route::get('/shift/detail/{id?}', [ShiftController::class, 'getById'])->middleware('can:shift_lihat','can:shift_simpan');
  Route::get('/shift/close/{id}', [ShiftController::class, 'getClose'])->middleware('can:shift_lihat','can:shift_tutup');
  Route::get('/shift/edit/{id}', [ShiftController::class, 'getEdit'])->middleware('can:shift_lihat','can:shift_simpan');
  Route::post('/shift/simpan', [ShiftController::class, 'save'])->middleware('can:shift_simpan');
  Route::post('/shift/edit', [ShiftController::class, 'edit'])->middleware('can:shift_simpan');
  Route::post('/shift/close', [ShiftController::class, 'close'])->middleware('can:shift_tutup');
  Route::post('/shift/hapus/{id}', [ShiftController::class, 'deleteById'])->middleware('can:shift_hapus');

  Route::get('/user', [UserController::class, 'index'])->middleware('can:user_lihat');
  Route::get('/user/grid', [UserController::class, 'getLists'])->middleware('can:user_lihat');
  Route::get('/user/detail/{id?}', [UserController::class, 'getById'])->middleware('can:user_lihat','can:user_simpan');
  Route::get('/user/cari', [UserController::class, 'searchUser']);
  Route::post('/user/simpan', [UserController::class, 'save'])->middleware('can:user_simpan');
  Route::post('/user/ubahpassword/{id}',[UserController::class, 'changePassword'])->middleware('can:user_simpan');
  Route::post('/user/hapus/{id}', [UserController::class, 'deleteById'])->middleware('can:user_hapus');
});


