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

// Сохранение языка интерфейса
Route::post('/language', function (Request $request) {
    $request->validate([
        'language' => 'required|in:ru,en',
    ]);

    // Сохраняем в сессии
    session(['locale' => $request->language]);

    // Устанавливаем локаль для текущего запроса
    app()->setLocale($request->language);

    // Если пользователь авторизован, можно сохранить в БД
    if ($request->user()) {
        // Здесь можно добавить сохранение в профиль пользователя
        // $request->user()->update(['preferred_language' => $request->language]);
    }

    return response()->json([
        'success' => true,
        'language' => $request->language,
    ])->cookie('locale', $request->language, 60 * 24 * 365); // Сохраняем в куки на год
});
