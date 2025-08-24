<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\AuthSessionController;
// use App\Http\Controllers\MailSendController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Controllers\DealingController;
use App\Mail\MailableMailtrap;
use Illuminate\Support\Facades\Mail;

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

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');
Route::post('/login', [LoginController::class, 'login'])->middleware('email');

Route::get('/item/:{item_id}', [ItemController::class, 'detail'])->name('item.detail');
Route::get('/', [ItemController::class, 'index'])->name('index');

Route::get('/search:{keyword?}', [ItemController::class, 'search'])->name('search');

Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
Route::post('/register', [RegisterController::class, 'register']);
Route::patch('/mypage/profile/update', [UserController::class, 'update']);

Route::get('/dealing/mail/comp', function() {
    $name = "testname";
    Mail::to('seller@test.com')->send(new MailableMailtrap($name));
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware('auth', 'verified')->group(function () {
    Route::patch('/dealing/{dealing_id}/complete', [DealingController::class, 'complete']);
    Route::patch('/dealing/{dealing_id}/delete', [DealingController::class, 'delete']);
    Route::patch('/dealing/{dealing_id}/edit', [DealingController::class, 'update']);
    Route::post('/dealing/{dealing_id}/message', [DealingController::class, 'store']);
    Route::get('/dealing/{dealing_id}', [DealingController::class, 'chat'])->name('dealing.chat');
    Route::get('/mypage{tab}', [UserController::class, 'mypage'])->name('mypage');
    Route::get('/mypage/profile', [UserController::class, 'profile'])->name('profile.edit');
    Route::get('/exhibition', [ItemController::class, 'create']);
    Route::post('/exhibition', [ItemController::class, 'store']);
});

Route::get('/mylist:{keyword?}', [ItemController::class, 'mylistAndKeyword'])->name('mylist.keyword');
Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');
Route::post('/item/:{item_id}/comment', [ItemController::class, 'comment'])->name('comment');

Route::middleware('auth', 'verified')->group(function () {
    Route::post('/item/:{item_id}/nice', [ItemController::class, 'nice'])->name('nice');

    Route::post('/purchase/address/:{item_id}/update', [PurchaseController::class, 'shippingUpdate'])->name('address.update');
    Route::get('/purchase/address/:{item_id}', [PurchaseController::class, 'editShipping']);
    Route::post('/purchase/:{item_id}/commit', [PurchaseController::class, 'store'])->name('purchase.commit');
    Route::get('/purchase/:{item_id}', [PurchaseController::class, 'purchase'])->name('purchase');
    
});


Auth::routes(['verify' => true]); //メール認証用
Route::get('/verify', [UserController::class, 'verify'])->name('verify'); //メール認証確認画面
// Route::get('/mail', [MailSendController::class, 'index']); //メール認証用

Route::get('/stripe/index', [PurchaseController::class, 'stripe'])->name('stripe');

Route::get('/email/verify', function() {
    return view('auth.verify');
})->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    session()->get('unauthenticated_user')->sendEmailVerificationNotification();
    session()->put('resent', true);
    return back()->with('message', 'Verification link sent!');
})->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    session()->forget('unauthenticated_user');
    return redirect('/mypage/profile');
})->middleware('auth')->name('verification.verify');

// Auth::routes();