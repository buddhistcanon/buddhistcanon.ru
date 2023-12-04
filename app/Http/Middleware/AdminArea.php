<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect(route('login'));
        }

        if (Auth::user()->is_superadmin) {
            return $next($request);
        }

        $allowedRoles = ['admin', 'editor_russian', 'editor_english', 'editor_pali'];
        $userRoles = Auth::user()->roles;
        $userRoles = $userRoles->pluck('name')->toArray();
        $intersect = array_intersect($userRoles, $allowedRoles);
        if (count($intersect) !== 0) {
            return $next($request);
        }

        abort(403, 'Access denied');
    }
}
