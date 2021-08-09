<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Frontend\NotificationController;
use Faker\Guesser\Name;

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


// User Login
Auth::routes();

// Admin Login
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// User Home

Route::middleware('auth')->group(function () {

    Route::get('/', [PageController::class, 'index'])->name('home');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    Route::get('/profile/update/password', [PageController::class, 'password'])->name('profile.password');
    Route::post('/profile/update/password', [PageController::class, 'updatePassword'])->name('profile.update.password');
    Route::get('/profile/update/image', [PageController::class, 'image'])->name('profile.image');
    Route::post('/profile/update/image', [PageController::class, 'imageUpdate'])->name('profile.update.image');
    Route::get('/wallet', [PageController::class, 'wallet'])->name('wallet');

    Route::get('/transfer', [PageController::class, 'transfer'])->name('transfer');
    Route::get('/transfer/confirm', [PageController::class, 'transferConfirm'])->name('transfer.confirm');
    Route::get('/phone/verify', [PageController::class, 'verify'])->name('phone.verify');
    Route::get('/password/check', [PageController::class, 'passwordCheck'])->name('password.check');
    Route::post('/transfer/complete', [PageController::class, 'transferComplete'])->name('transfer.complete');
    Route::get('/transfer/hash', [PageController::class, 'transferHash'])->name('transfer.hash');


    Route::get('/transaction', [PageController::class, 'transaction'])->name('transaction');
    Route::get('/transaction/details/{id}', [PageController::class, 'transactionDetail'])->name('transaction.detail');
    Route::get('/transaction/filter', [PageController::class, 'transactionFilter'])->name('transaction.filter');

    Route::get('/qr/receive-qr', [PageController::class, 'receiveQr'])->name('qr.receive.qr');
    Route::get('/qr/scan-pay', [PageController::class, 'scanAndPay'])->name('qr.scan.pay');

    Route::get('qr/transfer', [PageController::class, 'qrTransfer'])->name('qr.transfer');
    Route::get('qr/transfer/confirm', [PageController::class, 'qrTransferConfirm'])->name('qr.transfer.confirm');
    Route::post('qr/transfer/complete', [PageController::class, 'qrTransferComplete'])->name('qr.transfer.complete');


    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
});
