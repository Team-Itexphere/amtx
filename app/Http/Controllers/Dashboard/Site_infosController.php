<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site_infos;
use App\Models\User;

class Site_infosController extends Controller
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
        
        if ( request()->has('id') && request()->filled('id') ) {
            
            $user_Id = request()->input('id');
            
            if($role == 6 && auth()->user()->id != $user_Id){
                return abort(404);
            }
            
            $customer = User::find($user_Id);
            $site_info = $customer->site_info()->with('site_info_tanks')->first();
            if($site_info){
                $site_info->name = $customer->name;
                $site_info->tanks = $site_info->tanks;
            }
            
            return $site_info;            

        }

        return abort(404);
    }

    /*//for site info add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 4) {
            return abort(404);
        }
        
        $rules = [
            'brand' => 'required|string|max:100', 
            'id' => 'required|max:11',
        ];
        
        $request->validate($rules);
    
        $new_site_info_data = [
            'brand' => $request->input('brand'),
            'disp_type' => $request->input('disp_type'),
            'h_many_3_0' => $request->input('h_many_3_0'),
            'h_many_3_1' => $request->input('h_many_3_1'),
            'atg_type' => $request->input('atg_type'),
            'overfill_type' => $request->input('overfill_type'),
            'spill_b_brand' => $request->input('spill_b_brand'),
            'vent_brand' => $request->input('vent_brand'),
            'stp_model' => $request->input('stp_model'),
            'relay_brand' => $request->input('relay_brand'),
            'pos_system' => $request->input('pos_system'),
            'customer_id' => $request->input('id'),
        ];

        $site_info = Site_infos::create($new_site_info_data);
    
        return redirect()->back()->with('success', 'New site info added successfully!');

    }

    //for sit infos edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 4) {
            return abort(404);
        }
        
        $li_id = $request->input('li_id');
        $site_info = Site_infos::find($li_id);
            
        $rules = [
            'nw_brand' => 'required|string|max:100',
            'si_id' => 'required|max:11',
        ];
        
        $request->validate($rules);
    
        $new_site_info_data = [
            'brand' => $request->input('nw_brand'),
            'disp_type' => $request->input('nw_disp_type'),
            'h_many_3_0' => $request->input('nw_h_many_3_0'),
            'h_many_3_1' => $request->input('nw_h_many_3_1'),
            'atg_type' => $request->input('nw_atg_type'),
            'overfill_type' => $request->input('nw_overfill_type'),
            'spill_b_brand' => $request->input('nw_spill_b_brand'),
            'vent_brand' => $request->input('nw_vent_brand'),
            'stp_model' => $request->input('nw_stp_model'),
            'relay_brand' => $request->input('nw_relay_brand'),
            'pos_system' => $request->input('nw_pos_system'),
        ];

        $site_info->fill($new_site_info_data);
    
        $site_info->save();
    
        return redirect()->back()->with('success', 'Site info updated successfully!');

    }*/
    
    
    // For API
    function si_list($cus_id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {

            $customer = User::find($cus_id);
            $site_info = $customer->site_info()->with('site_info_tanks')->first();
            if(!$site_info){
                return response()->json(null, 200);
            }

            return $site_info;
            
        }

        return response()->json(['message' => 'Access denied'], 401);
    }
}
