<?php


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('auth.login');
Route::middleware('auth:sanctum')
    ->post('/auth/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('auth.logout');
