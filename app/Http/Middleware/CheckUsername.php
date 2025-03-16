<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUsername
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user() && Auth::user()->isAdmin) {
            return redirect()->route('home');
        } elseif (Auth::user()) {
            return redirect()->route('home');
        } else {
            return redirect()->route('login');
        }
    }
}
