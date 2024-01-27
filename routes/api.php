<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\VoucherController;
use Illuminate\Http\Request;
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
Route::prefix('{locale}')
    ->where(['locale' => '[a-zA-Z]{2}'])
    ->middleware('locale')
    ->group(function () {

        Route::post('signUp', [AuthController::class, 'signUp'])->name('signUp');
        Route::post('signIn', [AuthController::class, 'signIn'])->name('signIn');
        Route::post('reSendSms', [AuthController::class, 'reSendSms'])->name('reSendSms');
        Route::post('forgotPassword', [AuthController::class, 'forgotPassword'])->name('forgotPassword');

        Route::get('prizes', [PrizeController::class, 'prizes'])->name('prizes');

        Route::middleware('auth:sanctum')
            ->group(function() {

                Route::prefix('receipts')
                    ->name('receipts.')
                    ->group(function () {
                        Route::get('', [ReceiptController::class, 'receipts'])->name('list');
                        Route::post('recognize', [ReceiptController::class, 'recognize'])->name('recognize');
                    });

                Route::get('profile', [ProfileController::class, 'profile'])->name('profile');

                Route::get('invitations', [ProfileController::class, 'invitations'])->name('invitations');

                Route::post('vouchers', [VoucherController::class, 'buy'])->name('vouchers.buy');

                Route::prefix('games')
                    ->name('games.')
                    ->group(function () {
                        Route::get('start', [GameController::class, 'start'])->name('start');
                        Route::post('finish', [GameController::class, 'finish'])->name('finish');
                    });
            });
    });

