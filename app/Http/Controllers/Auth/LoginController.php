<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User_logs;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected function authenticated(Request $request, $user)
    {
        if(auth()->user()->role > 6 || auth()->user()->login != 1){
            Auth::logout();
            return redirect('login');
        }

        if(auth()->user()->role < 4 && auth()->user()->role != 1){
            if (auth()->check() && auth()->user()->role < 4 && auth()->user()->role != 1) {

                $last_log = User_logs::where('user_id', auth()->id())->whereNull('logout_at')->latest()->first();

                if($last_log){
                    $last_log->update(['logout_at' => now()]);
                }
                
                User_logs::create([
                    'user_id' => auth()->user()->id,
                    'login_at' => now(),
                ]);

            }            
        }
        
        switch(auth()->user()->role){
            case 1:
                $this->redirectTo = route('dashboard');
                break;
            case 2:
                $this->redirectTo = route('dashboard');
                break;
            case 3:
                $this->redirectTo = route('dashboard');
                break;
            case 4:
                $this->redirectTo = route('dashboard');
                break;
            default:
                $this->redirectTo = url('/login');
        }
        return redirect()->intended($this->redirectTo);
    }

    public function logout(Request $request)
    {
        if(auth()->user()->role < 4 && auth()->user()->role != 1){
            if (auth()->check() && auth()->user()->role < 4 && auth()->user()->role != 1) {

                $last_log = User_logs::where('user_id', auth()->id())->whereNull('logout_at')->latest()->first();

                if($last_log){
                    $last_log->update(['logout_at' => now()]);
                }

            }            
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
