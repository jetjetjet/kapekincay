<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
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

  Route::get('/meja', [BoardController::class, 'index']);
  Route::get('/meja/grid', [BoardController::class, 'getLists']);
  Route::get('/meja/detail/{id?}', [BoardController::class, 'getById']);
  Route::post('/meja/simpan', [BoardController::class, 'save']);
  Route::post('/meja/hapus/{id}', [BoardController::class, 'deleteById']);

  Route::get('/jabatan', [RoleController::class, 'index']);
  Route::get('/jabatan/grid', [RoleController::class, 'getLists']);
  Route::get('/jabatan/detail/{id?}', [RoleController::class, 'getById']);
  Route::post('/jabatan/simpan', [RoleController::class, 'save']);
  Route::post('/jabatan/hapus/{id}', [RoleController::class, 'deleteById']);

  Route::get('/user', [UserController::class, 'index']);
  Route::get('/user/grid', [UserController::class, 'getLists']);
  Route::get('/user/detail/{id?}', [UserController::class, 'getById']);
  Route::post('/user/simpan', [UserController::class, 'save']);
  Route::post('/user/ubahpassword/{id}',[UserController::class, 'changePassword']);
  Route::post('/user/hapus/{id}', [UserController::class, 'deleteById']);
});


