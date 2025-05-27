<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\MailSendController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ItemController;

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
    Route::get('/', [ItemController::class, 'index']);
});

Route::get('/mail', [MailSendController::class, 'index']);
