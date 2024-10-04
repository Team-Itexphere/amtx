<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SwitchUser
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
        if(Auth::check()){

            if($request->has('switch_back')){
                $user = Auth::user();
                $user->switch_as = null;
                $user->save();
                session()->forget('is_switched');
                return redirect()->route('dashboard');
            }
    
            if (Auth::user()->role == 1  && Auth::user()->switch_as) {
    
                $impersonatedUser = Auth::user()->switch_as;
                Auth::onceUsingId($impersonatedUser);
                session(['is_switched' => true]);
    
            } else {
    
                session()->forget('is_switched');
    
            }

        }

        return $next($request);
    }
}
