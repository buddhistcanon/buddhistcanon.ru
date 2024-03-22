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
    Route::get('/profile', \App\Http\Controllers\ProfileController::class.'@index');
});

Route::group(['middleware' => ['admin_area']], function () {
    Route::get('/admin', \App\Http\Controllers\Admin\AdminController::class.'@index');
    Route::get('/admin/suttas/{category}', \App\Http\Controllers\Admin\AdminController::class.'@suttas');
    Route::get('/admin/edit_sutta/{id}', \App\Http\Controllers\Admin\Suttas\AdminSuttaController::class.'@edit');
    Route::post('admin/store_sutta', \App\Http\Controllers\Admin\Suttas\AdminSuttaController::class.'@store');

    Route::get('/admin/terms', \App\Http\Controllers\Admin\Terms\AdminTermsController::class.'@index');
    Route::get('/admin/edit_term/{id}', \App\Http\Controllers\Admin\Terms\AdminTermsController::class.'@edit');
    Route::post('/admin/store_term', \App\Http\Controllers\Admin\Terms\AdminTermsController::class.'@store');

    Route::get('/admin/help', \App\Http\Controllers\Admin\AdminHelpController::class.'@index');

    Route::get('/admin/logs', \App\Http\Controllers\Admin\AdminLogsController::class.'@index');
});

Route::get('/', \App\Http\Controllers\WelcomeController::class.'@index')->name('welcome');
Route::get('/palicanon', \App\Http\Controllers\PalicanonController::class.'@index')->name('palicanon');

Route::get('/dn', \App\Http\Controllers\Canon\CanonController::class.'@dn');
Route::get('/mn', \App\Http\Controllers\Canon\CanonController::class.'@mn');
Route::get('/an', \App\Http\Controllers\Canon\CanonController::class.'@an');
Route::get('/an/{x}', \App\Http\Controllers\Canon\CanonController::class.'@an_x');
Route::get('/sn', \App\Http\Controllers\Canon\CanonController::class.'@sn');
Route::get('/sn/{x}', \App\Http\Controllers\Canon\CanonController::class.'@sn_x');

// Универсальный роут отображения сутты
Route::get('/{sutta}/{lang?}/{translator?}', \App\Http\Controllers\Canon\SuttaController::class.'@index')
    ->name('sutta')
    ->where([
        'sutta' => "(mn|an|sn|MN|AN|SN)([\d\.\-]*)",
    ]);
