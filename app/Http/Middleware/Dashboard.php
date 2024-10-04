<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Dashboard
{
    public function handle(Request $request, Closure $next)
    {
        /*if (auth()->user() && auth()->user()->role == 1) {
            return $next($request);
        }

        return redirect('login');*/

    }
}
