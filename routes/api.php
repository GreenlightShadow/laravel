<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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
Route::group([
    'prefix' => 'auth',
], function ()
{
    Route::post('/users', [UserController::class, 'store'])->name('store');
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/reset', [PasswordResetController::class, 'resetPassword'])->name('resetPassword');
    Route::post('/update', [PasswordResetController::class, 'updatePassword'])->name('updatePassword');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
