<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware'=>['is_login']],function(){
    Route::get('/register',[UserController::class, 'loadRegister']);
    Route::post('/register',[UserController::class, 'register'])->name('register');
    Route::get('/referral-register',[UserController::class, 'loadReferralRegister'])->name('load.register');
    Route::get('/email-verification/{token}',[UserController::class, 'emailVerification'])->name('register.emailVerification');
    Route::get('/login',[UserController::class,'loadLogin']);
    Route::post('/login',[UserController::class,'userLogin'])->name('login');
});

Route::group(['middleware'=>['is_logout']],function(){
    Route::get('/dashboard',[DashboardController::class,'index'])->name('');
    Route::get('/logout',[UserController::class,'logout'])->name('logout');
    Route::get('/referraltrack',[DashboardController::class,'referralTrack'])->name('referralTrack');
});