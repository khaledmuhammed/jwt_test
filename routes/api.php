<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/contacts', ContactController::class);


// JWT Routes
Route::controller(LoginController::class)->name('auth.')->prefix('auth')->group(function (){
    Route::post('/register',  'register')->name('register');
    Route::post('/reset_password',  'reset_password')->name('reset_password');

    Route::post('/login',  'login')->name('login');
    Route::get('/profile',  'profile')->name('profile');
    Route::get('/logout',  'logout')->name('logout');
});

