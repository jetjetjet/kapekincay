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

Route::group(array('middleware' => 'auth'), function ()
{
  Route::get('/', [DashboardController::class, 'index']);
 
  Route::get('/jabatan', [RoleController::class, 'index']);
  Route::get('/jabatan/grid', [RoleController::class, 'getLists']);
  Route::get('/jabatan/detail/{id?}', [RoleController::class, 'getById']);
  Route::post('/jabatan/simpan', [RoleController::class, 'save']);
  Route::post('/jabatan/hapus/{id}', [RoleController::class, 'deleteById']);

  Route::get('/cust', [CustomerController::class, 'index']);
  Route::get('/cust/grid', [CustomerController::class, 'getLists']);
  Route::get('/cust/detail/{id?}', [CustomerController::class, 'getById']);
  Route::post('/cust/simpan', [CustomerController::class, 'save']);
  Route::post('/cust/hapus/{id}', [CustomerController::class, 'deleteById']);
  
  Route::get('/meja', [BoardController::class, 'index']);
  Route::get('/meja/grid', [BoardController::class, 'getLists']);
  Route::get('/meja/detail/{id?}', [BoardController::class, 'getById']);
  Route::post('/meja/simpan', [BoardController::class, 'save']);
  Route::post('/meja/hapus/{id}', [BoardController::class, 'deleteById']);

  Route::get('/menu', [MenuController::class, 'index']);
  Route::get('/menu/grid', [MenuController::class, 'getLists']);
  Route::get('/menu/detail/{id?}', [MenuController::class, 'getById']);
  Route::post('/menu/simpan', [MenuController::class, 'save']);
  Route::post('/menu/hapus/{id}', [MenuController::class, 'deleteById']);
  Route::get('image-upload', [ MenuController::class, 'imageUpload' ]);
  Route::post('image-upload', [ MenuController::class, 'imageUploadPost' ]);

  Route::get('/setting', [SettingController::class, 'index']);
  Route::get('/setting/grid', [SettingController::class, 'getLists']);
  Route::get('/setting/detail/{id?}', [SettingController::class, 'getById']);
  Route::post('/setting/simpan', [SettingController::class, 'save']);

  Route::get('/shift', [ShiftController::class, 'index']);
  Route::get('/shift/grid', [ShiftController::class, 'getLists']);
  Route::get('/shift/detail/{id?}', [ShiftController::class, 'getById']);
  Route::get('/shift/close/{id}', [ShiftController::class, 'getClose']);
  Route::get('/shift/edit/{id}', [ShiftController::class, 'getEdit']);
  Route::post('/shift/simpan', [ShiftController::class, 'save']);
  Route::post('/shift/edit', [ShiftController::class, 'edit']);
  Route::post('/shift/close', [ShiftController::class, 'close']);
  Route::post('/shift/hapus/{id}', [ShiftController::class, 'deleteById']);

  Route::get('/user', [UserController::class, 'index']);
  Route::get('/user/grid', [UserController::class, 'getLists']);
  Route::get('/user/detail/{id?}', [UserController::class, 'getById']);
  Route::post('/user/simpan', [UserController::class, 'save']);
  Route::post('/user/ubahpassword/{id}',[UserController::class, 'changePassword']);
  Route::post('/user/hapus/{id}', [UserController::class, 'deleteById']);
});


