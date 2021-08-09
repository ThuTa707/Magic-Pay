<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageApiController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware(['auth:api'])->group(function () {

Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/profile', [PageApiController::class, 'profile']);

Route::get('/transaction', [PageApiController::class, 'transaction']);
Route::get('/transaction/details/{id}', [PageApiController::class, 'transactionDetail']);


Route::get('/notifications', [PageApiController::class, 'notifications']);
Route::get('/notifications/{id}', [PageApiController::class, 'notificationShow']);

Route::get('/phone/verify', [PageApiController::class, 'verify']);
Route::get('/transfer/confirm', [PageApiController::class, 'transferConfirm']);
Route::post('/transfer/complete', [PageApiController::class, 'transferComplete']);

Route::get('qr/transfer', [PageApiController::class, 'qrTransfer']);
Route::get('qr/transfer/confirm', [PageApiController::class, 'qrTransferConfirm']);
Route::post('qr/transfer/complete', [PageApiController::class, 'qrTransferComplete']);

});