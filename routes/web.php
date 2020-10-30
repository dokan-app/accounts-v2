<?php

use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome');


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('settings.profile');
    })->name('dashboard');

    // oauths
    Route::view('oauth/clients', 'oauth.clients')->name('app-oauth.clients');
    Route::view('oauth/personal-access-tokens', 'oauth.personal-access-tokens')->name('app-oauth.personal-access-tokens');
    Route::view('oauth/authorized-services', 'oauth.authorized-services')->name('app-oauth.authorized-services');


    // User
    Route::view('profile', 'user-settings.profile')->name('settings.profile');
    Route::view('password', 'user-settings.password')->name('settings.password');

    Route::post('profile', [AuthController::class, 'updateProfile']);
    Route::post('password', [AuthController::class, 'updatePassword']);
});

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::view('/login', 'auth.login')->name('auth.login');
    Route::view('/register', 'auth.register')->name('auth.register');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::delete('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth/verify', [AuthController::class, 'verifyMail'])->name('verification.verify');
