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

Auth::routes(['verify' => true]); //メール認証用

Route::get('/search:{keyword?}', [ItemController::class, 'search'])->name('search');

Route::get('/register', [RegisterController::class, 'create']);
Route::post('/register', [RegisterController::class, 'store']);
Route::patch('/mypage/profile/update', [UserController::class, 'update']);
Route::get('/login', [AuthSessionController::class, 'login'])->name('login');
Route::post('/login', [AuthSessionController::class, 'store']);
Route::get('/logout', [AuthSessionController::class, 'destroy'])->name('logout');
Route::get('/verify', [UserController::class, 'verify'])->name('verify'); //メール認証確認画面

Route::middleware('auth')->group(function () {
    Route::get('/mypage{tab}', [UserController::class, 'mypage'])->name('mypage');
    Route::get('/mypage/profile', [UserController::class, 'profile'])->name('profile.edit')->middleware( 'verified');
    Route::get('/exhibition', [ItemController::class, 'create']);
    Route::post('/exhibition', [ItemController::class, 'store']);
});

Route::get('/mylist:{keyword?}', [ItemController::class, 'mylistAndKeyword'])->name('mylist.keyword');

Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');

Route::post('/item/:{item_id}/comment', [ItemController::class, 'comment'])->name('comment');

Route::middleware('auth')->group(function () {
    Route::post('/item/:{item_id}/nice', [ItemController::class, 'nice'])->name('nice');

    Route::post('/purchase/address/:{item_id}/update', [PurchaseController::class, 'shippingUpdate'])->name('address.update');
    Route::get('/purchase/address/:{item_id}', [PurchaseController::class, 'editShipping']);
    Route::post('/purchase/:{item_id}/commit', [PurchaseController::class, 'store'])->name('purchase.commit');
    Route::get('/purchase/:{item_id}', [PurchaseController::class, 'purchase'])->name('purchase');
    
});

Route::get('/item/:{item_id}', [ItemController::class, 'detail'])->name('item.detail');

Route::get('/mail', [MailSendController::class, 'index']); //メール認証用

Route::get('/', [ItemController::class, 'index'])->name('index');