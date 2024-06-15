<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class SuperAdminArea
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

        abort(403, 'Access denied');
    }
}
