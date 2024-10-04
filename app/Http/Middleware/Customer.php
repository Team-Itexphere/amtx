<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Customer
{
    public function handle(Request $request, Closure $next)
    {
        /*if (auth()->user() && auth()->user()->role == 2) {
            return $next($request);
        }

        return redirect('login');*/

    }
}
