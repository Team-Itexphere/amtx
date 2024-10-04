<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Fuel_tanks;

class Fuel_tanksController extends Controller
{
    //for fuel tank add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $rules = [
            'ft_name' => 'required|string|max:300',
            'store' => 'required',
        ];
        $rules['ft_type'] = $request->input('ft_type') ? 'string|max:300' : '';
        $rules['ft_capacity'] = $request->input('ft_capacity') ? 'max:20' : '';
        $rules['ft_installation_date'] = $request->input('ft_installation_date') ? 'max:20' : '';
        $rules['ft_installed_by'] = $request->input('ft_installed_by') ? 'string|max:300' : '';
        $rules['tmc_doc'] = $request->file('tmc_doc') ? 'mimes:pdf|max:20480' : '';
            
        $request->validate($rules);
    
        $fuel_tank_data = [
            'name' => $request->input('ft_name'),
            'type' => $request->input('ft_type'),
            'capacity' => $request->input('ft_capacity'),
            'installation_date' => $request->input('ft_installation_date'),
            'installed_by' => $request->input('ft_installed_by'),
            'store_id' => $request->input('store'),
        ];

        if ($request->hasFile('tmc_doc')) {
            $store_id = $request->input('store');
            $ft_name = $request->input('ft_name');
            
            $customName = $ft_name .  ' - STR_' . $store_id;
            $fileName = $customName . ' - ' . str_replace('#', '', $request->file('tmc_doc')->getClientOriginalName());

            $request->file('tmc_doc')->move(public_path('tank-monitor-charts') . "/{$store_id}/", $fileName);

            $new_license_data['tmc_path'] = $store_id . '/' . $fileName;
        }
            
        $fuel_tank = Fuel_tanks::create($fuel_tank_data);
    
        /*if ($fuel_tank) {
            return response()->json(['message' => 'Fuel tank added successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to add Fuel tank'], 500);
        }*/
    
        return redirect()->back()->with('success', 'New fuel tank added successfully!');

    }

    //for fuel tank edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $ft_id = $request->input('ft_id');
        $fuel_tank = Fuel_tanks::find($ft_id);
            
        $rules = [
            'nw_ft_name' => 'required|string|max:300',
            'nw_ft_store' => 'required',
        ];
        $rules['nw_ft_type'] = $request->input('nw_ft_type') ? 'string|max:300' : '';
        $rules['nw_ft_capacity'] = $request->input('nw_ft_capacity') ? 'max:20' : '';
        $rules['nw_ft_installation_date'] = $request->input('nw_ft_installation_date') ? 'max:20' : '';
        $rules['nw_ft_installed_by'] = $request->input('nw_ft_installed_by') ? 'string|max:300' : '';
        $rules['nw_tmc_doc'] = $request->file('nw_tmc_doc') ? 'mimes:pdf|max:20480' : '';
            
        $request->validate($rules);
    
        $new_fuel_tank_data = [
            'name' => $request->input('nw_ft_name'),
            'type' => $request->input('nw_ft_type'),
            'capacity' => $request->input('nw_ft_capacity'),
            'installation_date' => $request->input('nw_ft_installation_date'),
            'installed_by' => $request->input('nw_ft_installed_by'),
            'store_id' => $request->input('nw_ft_store'),
        ];

        if ($request->hasFile('nw_tmc_doc')) {
            $store_id = $request->input('nw_ft_store');
            $nw_ft_name = $request->input('nw_ft_name');
            
            $customName = $nw_ft_name .  ' - STR_' . $store_id;
            $fileName = $customName . ' - ' . str_replace('#', '', $request->file('nw_tmc_doc')->getClientOriginalName());

            $request->file('nw_tmc_doc')->move(public_path('tank-monitor-charts') . "/{$store_id}/", $fileName);

            $new_fuel_tank_data['tmc_path'] = $store_id . '/' . $fileName;
        }

        $fuel_tank->fill($new_fuel_tank_data);
    
        $fuel_tank->save();
    
        return redirect()->back()->with('success', 'Fuel tank updated successfully!');

    }
}
