<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login/authenticate', [LoginController::class, "authenticate"])->name('authenticate');

Route::group(['middleware' => 'jwt.auth'], function() {
    Route::post('login/user', [LoginController::class, "user"])->name('login.user');
});