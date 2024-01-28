<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('{locale}')
    ->where(['locale' => '[a-zA-Z]{2}'])
    ->middleware('locale')
    ->group(function () {
        Route::get('', function () {
            return view('index');
        })->name('index');
        Route::get('profile', function () {
            return view('profile');
        });
    });
Route::get('/', function () {
    $locale = session()->get('locale', app()->getLocale());

    return redirect()->route('index', ['locale' => $locale]);
});

Route::get('/', function () {
    $locale = session()->get('locale', app()->getLocale());

    return redirect()->route('index', ['locale' => $locale]);
});

Route::get('/game', [GameController::class, 'gamePage'])->name('gamePage');
