<?php

use Illuminate\Foundation\Application;
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

require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profile', \App\Http\Controllers\ProfileController::class.'@index')->name('profile');
});

Route::group(['middleware' => ['admin']], function () {
    Route::get('/profile', \App\Http\Controllers\ProfileController::class.'@index')->name('profile');
});

Route::get('/', \App\Http\Controllers\WelcomeController::class.'@index')->name('welcome');

Route::get('/dn', \App\Http\Controllers\Canon\CanonController::class.'@dn')->name('dn');
Route::get('/mn', \App\Http\Controllers\Canon\CanonController::class.'@mn')->name('mn');
Route::get('/an', \App\Http\Controllers\Canon\CanonController::class.'@an')->name('an');
Route::get('/sn', \App\Http\Controllers\Canon\CanonController::class.'@sn')->name('sn');

// Универсальный роут отображения сутты
Route::get('/{sutta}/{lang?}/{translator?}', \App\Http\Controllers\Canon\SuttaController::class.'@index')
    ->name('sutta')
    ->where([
        'sutta' => "(mn|an|sn|MN|AN|SN)([\d\.]*)",
    ]);
