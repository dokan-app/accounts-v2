<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome');

//Route::get('/dashboard', function () {
//    return view('dashboard.root');
//})->name('dashboard')->middleware('auth');


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('dashboard.root');
    })->name('dashboard');

    Route::get('/manage-oauth', function () {
        return view('dashboard.oauth');
    })->name('oauth');
});

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::view('/login', 'auth.login')->name('auth.login');
    Route::view('/register', 'auth.register')->name('auth.register');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::delete('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
