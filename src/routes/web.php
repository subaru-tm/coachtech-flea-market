<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\MailSendController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;

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

Route::get('/register', [RegisterController::class, 'create']);
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/mypage/profile', [UserController::class, 'create']);
Route::patch('/mypage/profile/update', [UserController::class, 'update']);
Route::get('/login', [AuthSessionController::class, 'login'])->name('login');
Route::post('/login', [AuthSessionController::class, 'store']);
Route::get('/logout', [AuthSessionController::class, 'destroy']);


Route::middleware('auth')->group(function () {
    Route::get('/mypage{tab}', [UserController::class, 'mypage']);
    Route::get('/mypage/profile', [UserController::class, 'profile']);
    Route::get('/mylist', [ItemController::class, 'mylist']);
    Route::get('/exhibition', [ItemController::class, 'create']);
    Route::post('/exhibition', [ItemController::class, 'store']);
});

Route::get('/', [ItemController::class, 'index'])->name('index');

Route::get('/{keyword}', [ItemController::class, 'search']);



Route::middleware('auth')->group(function () {
    Route::post('/item/:{item_id}/nice', [ItemController::class, 'nice']);
    Route::post('/item/:{item_id}/comment', [ItemController::class, 'comment']);

    Route::get('/purchase/:{item_id}', [PurchaseController::class, 'purchase'])->name('purchase');
    Route::get('/purchase/address/:{item_id}', [PurchaseController::class, 'updateShipping']);
    Route::post('/purchase/address/:{item_id}/update', [PurchaseController::class, 'shippingUpdate']);
    Route::post('/purchase/:{item_id}/commit', [PurchaseController::class, 'store']);

    
});

Route::get('/item/:{item_id}', [ItemController::class, 'detail'])->name('item.detail');


Route::get('/mail', [MailSendController::class, 'index']);
