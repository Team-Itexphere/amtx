<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Maintain_logs;
use App\Models\User;

class Maintain_logsController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $perPage = request('per_page', 10);
        $page = request('page', 1);

        $s = request('s', '');
        
        $role = auth()->user()->role;
        
        if ( request()->has('id') && request()->filled('id') ) {
            
            $user_Id = request()->input('id');
            $customer = User::find($user_Id);
            $auth_user = auth()->user();
            
            if($role == 6 && $auth_user->id != $user_Id && !$auth_user->stores()->where('id', $customer->id)->exists()){
                return abort(404);
            }
            
            $maintain_logs = $customer->maintain_logs;
            $maintain_logs = paginateCollection($maintain_logs, $perPage, $page);
            
            return view('dashboard', compact('maintain_logs', 'customer'));

        }

        return abort(404);
    }

    // For API
    function list(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {

            $maintain_logs = Maintain_logs::where('cus_id', request()->input('cus_id'))->get();
            
            foreach($maintain_logs as $log){
                $log['tech_name'] = $log->tech_id ? $log->technician->name : '';
                $log->makeHidden('technician');
                
                $log['company'] = null;
                if($log->cus_id && $log->customer->com_to_inv){
                    if($log->customer->com_to_inv == 'AMTS'){
                        $log['company'] = 'AMTS';
                    } else {
                        $log['company'] = 'PTS';
                    }
                    
                    $log->makeHidden('customer');
                }
            }

            return $maintain_logs;
            
        }

        return response()->json(['message' => 'Access denied'], 401);
    }
}
