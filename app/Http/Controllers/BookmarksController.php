<?php

namespace App\Http\Controllers;

use App\Models\Sutta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarksController extends Controller
{
    /**
     * Показать список закладок пользователя
     */
    public function index()
    {
        $user = Auth::user();
        $bookmarks = $user->bookmarks()
            ->with('contents')
            ->with('contents.translator')
            ->orderByPivot('created_at', 'desc')
            ->get();

        return inertia('User/Bookmarks', [
            'bookmarks' => $bookmarks,
        ]);
    }

    /**
     * Добавить сутту в закладки
     */
    public function store(Request $request)
    {
        $request->validate([
            'sutta_id' => 'required|exists:suttas,id',
        ]);

        $user = Auth::user();
        $sutta = Sutta::findOrFail($request->sutta_id);

        // Проверяем, не добавлена ли уже эта сутта в закладки
        if ($user->bookmarks()->where('suttas.id', $sutta->id)->exists()) {
            return response()->json([
                'message' => 'Сутта уже в закладках',
                'is_bookmarked' => true,
            ], 200);
        }

        $user->bookmarks()->attach($sutta->id);

        return response()->json([
            'message' => 'Сутта добавлена в закладки',
            'is_bookmarked' => true,
        ], 201);
    }

    /**
     * Удалить сутту из закладок
     */
    public function destroy($suttaId)
    {
        $user = Auth::user();
        $sutta = Sutta::findOrFail($suttaId);

        $user->bookmarks()->detach($sutta->id);

        return response()->json([
            'message' => 'Сутта удалена из закладок',
            'is_bookmarked' => false,
        ], 200);
    }

    /**
     * Проверить, есть ли сутта в закладках
     */
    public function check($suttaId)
    {
        $user = Auth::user();
        $isBookmarked = $user->bookmarks()->where('suttas.id', $suttaId)->exists();

        return response()->json([
            'is_bookmarked' => $isBookmarked,
        ]);
    }
}
