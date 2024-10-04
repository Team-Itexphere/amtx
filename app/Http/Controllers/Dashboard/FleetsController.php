<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Fleets;

class FleetsController extends Controller
{
    //views for par
    public function index()
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $perPage = request('per_page', 10);
        $page = request('page', 1);

        $type = request('type', '');
        $type = ( $type == 'equip' ? 'Equipment' : ( $type == 'f_tank' ? 'Fuel Tank' : ( $type == 'pump' ? 'Pump' : ( $type == 'general' ? 'General' : '' ))));
        $store = request('store', '');
        $s = request('s', '');
        $fltRole = request('role', '');
        $dateRange  = request('d_range', '');
        
        $role = auth()->user()->role;
        
        if ( $role < 5 ) {
            
            if (request()->has('add')) {
                return view('dashboard');
            }
            
            if (request()->has('edit')) {
                if (request()->filled('edit')) {
                    $fleetId = request()->input('edit');
                    
                    $fleet = Fleets::find($fleetId);

                    if ( $role < 3 ) {
                        return view('dashboard', compact('fleet'));
                    }
                }
                return redirect('dashboard/fleet');
            }

            //$fleets = filterRecords(Fleets::class, null, $type, null, $s, $fltRole);
            $fleets = Fleets::all();

            $fleets = paginateCollection($fleets, $perPage, $page);

            return view('dashboard', compact('fleets'));

        }

        return redirect('login');
    }

    //for user add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;

        /*if ($role > $request->input('role') || $role > 3) {
            return redirect('/dashboard/employees?add')->with('error', 'Access denied');
        }*/
        
        if ( $role < 5 ) {
        
            $rules = [
                'fleet_no' => 'required|max:60',
            ];
            /*$rules['address'] = $request->input('address') ? 'string|max:300' : '';
            $rules['id_num'] = $request->input('id_num') ? 'string|max:50' : '';
            $rules['ssn'] = $request->input('ssn') ? 'string|max:300' : '';
            $rules['pay'] = $request->input('pay') ? 'numeric' : '';
            $rules['lpg_date'] = $request->input('lpg_date') ? 'date' : '';
            $rules['hire_date'] = $request->input('hire_date') ? 'date' : '';
            $rules['shift'] = $request->input('shift') ? 'string|max:60' : '';
            $rules['docs'] = $request->file('docs') ? 'mimes:pdf|max:20480' : ''; */
            
            $request->validate($rules);
    
            $new_fleet_data = [
                'fleet_no' => $request->input('fleet_no'),
                'license_pl_no' => $request->input('license_pl_no'),
                'vin_no' => $request->input('vin_no'),
                'gas_type' => $request->input('gas_type'),
                'start_millage' => $request->input('start_millage'),
                'stop_millage' => $request->input('stop_millage'),
                'date' => $request->input('date'),
            ];
            
            $fleet = Fleets::create($new_fleet_data);
    
            return redirect('/dashboard/fleet?add')->with('success', 'New fleet created successfully!');
        } else {
            return redirect('login');
        }
    }

    //for user edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role < 5 ) {
            $fleet_id = $request->input('ft_id');
            $fleet = Fleets::find($fleet_id);
            
            if ($role > 3) {
                return redirect('/dashboard/fleet?edit='.$fleet_id)->with('error', 'Access denied');
            }
    
            /*$rules = [
                'name' => 'required|string|max:255',
                'role' => 'required|in:1,2,3,4,5,6',
            ];
            $rules['email'] = $user->email != $request->input('email') ? 'required|string|email|max:50|unique:users' : '';
            $rules['phone'] = $request->input('phone') ? 'max:20' : '';
            $rules['address'] = $request->input('address') ? 'string|max:300' : '';
            $rules['id_num'] = $request->input('id_num') ? 'string|max:50' : '';
            $rules['ssn'] = $request->input('ssn') ? 'string|max:300' : '';
            $rules['pay'] = $request->input('pay') ? 'numeric' : '';
            $rules['lpg_date'] = $request->input('lpg_date') ? 'date' : '';
            $rules['hire_date'] = $request->input('hire_date') ? 'date' : '';
            $rules['shift'] = $request->input('shift') ? 'string|max:60' : '';
            $rules['password'] = $request->input('password') ? 'string|min:5' : '';
            $rules['docs'] = $request->file('docs') ? 'mimes:pdf|max:20480' : '';
    
            $request->validate($rules);*/
    
            $fleet->fleet_no = $request->input('fleet_no');
            $fleet->license_pl_no = $request->input('license_pl_no');
            $fleet->vin_no = $request->input('vin_no');
            $fleet->gas_type = $request->input('gas_type');
            $fleet->start_millage = $request->input('start_millage');
            $fleet->stop_millage = $request->input('stop_millage');
            $fleet->date = $request->input('date');
    
            $fleet->save();
    
            return redirect('/dashboard/fleet?edit='.$fleet_id)->with('success', 'Fleet updated successfully!');
        } else {
            return redirect('login');
        }
    }
    
    
    // For API

    //for fleet list
    function list($id = null)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 || $role == 1 || $role == 2 ) {

            return $id ? Fleets::find($id) : Fleets::all();
        }

        return response()->json(['message' => 'Access Denied'], 401);
    }
}
