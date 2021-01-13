<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BoardController;
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

Route::get('/', [DashboardController::class, 'index']);

Route::get('/meja', [BoardController::class, 'index']);
Route::get('/meja/grid', [BoardController::class, 'getLists']);
Route::get('/meja/detail/{id?}', [BoardController::class, 'getById']);
Route::post('/meja/simpan', [BoardController::class, 'save']);
Route::post('/meja/hapus/{id}', [BoardController::class, 'deleteById']);
