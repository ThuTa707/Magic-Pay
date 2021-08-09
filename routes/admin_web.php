<?php

use App\Http\Controllers\Backend\AdminUserController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WalletController;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:admin_user')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [PageController::class, 'home'])->name('home');

    // Admin Users
    Route::resource('admin-users', 'Backend\AdminUserController'); // Vsersion 7 Style 'Root pr thy chr htae pay ya'
    Route::get('/datatable', [AdminUserController::class, 'datatable'])->name('datatable');

    // Users
    Route::resource('users', 'Backend\UserController');
    Route::get('/datatable/users', [UserController::class, 'datatable'])->name('datatable.users');

    // Wallet
    Route::get('/wallets' , [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/datatable/wallets', [WalletController::class, 'datatable'])->name('datatable.wallets');
    Route::get('/add/amount', [WalletController::class, 'addAmount'])->name('add.amount');
    Route::post('/add/amount', [WalletController::class, 'addAmountWallet'])->name('add.amount.wallet');

    Route::get('/reduce/amount', [WalletController::class, 'reduceAmount'])->name('reduce.amount');
    Route::post('/reduce/amount', [WalletController::class, 'reduceAmountWallet'])->name('reduce.amount.wallet');


});


