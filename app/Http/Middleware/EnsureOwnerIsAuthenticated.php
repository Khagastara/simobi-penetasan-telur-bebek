<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOwnerIsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isOwner()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
