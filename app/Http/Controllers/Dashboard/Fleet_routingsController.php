<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Fleet_routings;
use App\Models\Fleets;
use App\Models\Work_orders;

class Fleet_routingsController extends Controller
{
    //views for par
    public function index()
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $perPage = request('per_page', 10);
        $page = request('page', 1);

        $s = request('s', '');
        $dateRange  = request('d_range', '');
        
        $role = auth()->user()->role;
        
        if ( $role < 5 ) {
            
            if (request()->has('view') && request()->filled('view')) {
                $fleet_Id = request()->input('view');

                $fleet = Fleets::find($fleet_Id);
                    
                $fleet_rs = Fleet_routings::where('fleet_id', $fleet_Id)->get();

                $fleet_rs = paginateCollection($fleet_rs, $perPage, $page);

                return view('dashboard', compact('fleet_rs', 'fleet'));
            }

            return redirect('dashboard/fleet');

        }

        return redirect('login');
    }

    
    // For API

    //for fleet routing list
    public function list(Request $request, $fleet_id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        $user_id = auth()->user()->id;
        
        if ( $role == 4 || $role == 5 || $role == 1 || $role == 2 ) {

            if($role == 5){
                $rel_wos_cnt = Work_orders::where('tech_id', $user_id)->where('fleet_id', $fleet_id)->count();
            } else {
                $rel_wos_cnt = Work_orders::where('fleet_id', $fleet_id)->count();
            }
            

            if($rel_wos_cnt > 0){
                $fleet_rs = Fleet_routings::where('fleet_id', $fleet_id)->get();

                return response()->json($fleet_rs);
            }

            return response($fleet_rs);
        }

        return response()->json(['message' => 'Access Denied'], 401);
    }

    //for fleet routing add
    public function add(Request $request, $fleet_id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;

        if ( $role == 4 || $role == 5 ) {
        
            $rules = [
                'date' => 'required|max:60',
                'start_millage' => 'required|max:60',
                'stop_millage' => 'required|max:60',
            ];
            /*$rules['address'] = $request->input('address') ? 'string|max:300' : '';*/
            
            $request->validate($rules);
    
            $new_fleet_r_data = [
                'fleet_id' => $fleet_id,
                'date' => $request->input('date'),
                'start_millage' => $request->input('start_millage'),
                'stop_millage' => $request->input('stop_millage'),
            ];
            
            $fleet_r = Fleet_routings::create($new_fleet_r_data);
    
            return response()->json(['message' => 'Fleet routing added successfully']);
        } else {
            return response()->json(['message' => 'Access Denied'], 401);
        }
    }

    //for fleet routing edit
    public function edit(Request $request, $fleet_id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {
            $fleet_r_id = $request->input('fleet_r_id');
            $fleet_r = Fleet_routings::find($fleet_r_id);
    
            $rules = [
                'date' => 'required|max:60',
                'start_millage' => 'required|max:60',
                'stop_millage' => 'required|max:60',
            ];
            /*$rules['email'] = $user->email != $request->input('email') ? 'required|string|email|max:50|unique:users' : '';*/
    
            $request->validate($rules);
    
            $fleet_r->date = $request->input('date');
            $fleet_r->start_millage = $request->input('start_millage');
            $fleet_r->stop_millage = $request->input('stop_millage');
    
            $fleet_r->save();
    
            return response()->json(['message' => 'Fleet routing updated successfully']);
        } else {
            return redirect('login');
        }
    }
}
