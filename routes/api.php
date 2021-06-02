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


Route::post('/register', [UserController::class, 'store'])->name('store');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/reset', [PasswordResetController::class, 'resetPassword'])->name('resetPassword');
Route::post('/update', [PasswordResetController::class, 'updatePassword'])->name('updatePassword');


Route::middleware('auth:api')->put('auth/update/{id}', [UserController::class, 'update'])->name('update');
Route::middleware('auth:api')->get('auth/users', [UserController::class, 'getUsers'])->name('get-users');
Route::middleware('auth:api')->get('auth/users/{id}', [UserController::class, 'getUserData'])->name('get-user-data');
