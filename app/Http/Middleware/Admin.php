<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect(route('login'));
        }
        if (optional(Auth::user())->isAdmin() or optional(Auth::user())->is_superadmin) {
            return $next($request);
        }

        abort(403, 'Access denied');
    }
}
