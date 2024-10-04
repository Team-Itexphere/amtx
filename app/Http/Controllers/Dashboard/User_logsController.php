<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User_logs;

class User_logsController extends Controller
{
    public function index()
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $perPage = request('per_page', 10);
        $page = request('page', 1);

        $s = request('s', '');
        
        $role = auth()->user()->role;
        
        if ( $role == 1 ) {
            
            $user_logs = User_logs::all();
            
            $user_logs = paginateCollection($user_logs, $perPage, $page);

            return view('dashboard', compact('user_logs'));            

        }

        return abort(404);
    }
}
