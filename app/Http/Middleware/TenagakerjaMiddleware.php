<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenagakerjaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'tenagakerja') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak.');
    }
}
