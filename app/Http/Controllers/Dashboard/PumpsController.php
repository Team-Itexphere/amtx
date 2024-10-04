<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pumps;

class PumpsController extends Controller
{
    //for pump add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $rules = [
            'pump_number' => 'required|string|max:60',
            'store' => 'required',
        ];
        $rules['dispense_type'] = $request->input('pmp_dispense_type') ? 'string|max:300' : '';
        $rules['model'] = $request->input('pmp_model') ? 'string|max:300' : '';
        $rules['payment_type'] = $request->input('pmp_payment_type') ? 'string|max:300' : '';
        $rules['installed_date'] = $request->input('pmp_installed_date') ? 'max:20' : '';
        $rules['warr_info'] = $request->input('pmp_warr_info') ? 'string|max:600' : '';
        $rules['purch_date'] = $request->input('pmp_purch_date') ? 'max:20' : '';
        $rules['purch_from'] = $request->input('pmp_purch_from') ? 'string|max:60' : '';
            
        $request->validate($rules);
    
        $pump_data = [
            'pump_number' => $request->input('pump_number'),
            'dispense_type' => $request->input('pmp_dispense_type'),
            'model' => $request->input('pmp_model'),
            'payment_type' => $request->input('pmp_payment_type'),
            'installed_date' => $request->input('pmp_installed_date'),
            'warr_info' => $request->input('pmp_warr_info'),
            'purch_date' => $request->input('pmp_purch_date'),
            'purch_from' => $request->input('pmp_purch_from'),
            'store_id' => $request->input('store'),
        ];
            
        $pump = Pumps::create($pump_data);
    
        /*if ($pump) {
            return response()->json(['message' => 'Pump added successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to add Pump'], 500);
        }*/
    
        return redirect()->back()->with('success', 'New pump added successfully!');

    }

    //for pump edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $pmp_id = $request->input('pmp_id');
        $pump = Pumps::find($pmp_id);
            
        $rules = [
            'nw_pump_number' => 'required|string|max:60',
            'nw_pmp_store' => 'required',
        ];
        $rules['nw_pmp_dispense_type'] = $request->input('nw_pmp_dispense_type') ? 'string|max:300' : '';
        $rules['nw_pmp_model'] = $request->input('nw_pmp_model') ? 'string|max:300' : '';
        $rules['nw_pmp_payment_type'] = $request->input('nw_pmp_payment_type') ? 'string|max:300' : '';
        $rules['nw_pmp_installed_date'] = $request->input('nw_pmp_installed_date') ? 'max:20' : '';
        $rules['nw_pmp_warr_info'] = $request->input('nw_pmp_warr_info') ? 'string|max:600' : '';
        $rules['nw_pmp_purch_date'] = $request->input('nw_pmp_purch_date') ? 'max:20' : '';
        $rules['nw_pmp_purch_from'] = $request->input('nw_pmp_purch_from') ? 'string|max:60' : '';
            
        $request->validate($rules);
    
        $new_pump_data = [
            'pump_number' => $request->input('nw_pump_number'),
            'dispense_type' => $request->input('nw_pmp_dispense_type'),
            'model' => $request->input('nw_pmp_model'),
            'payment_type' => $request->input('nw_pmp_payment_type'),
            'installed_date' => $request->input('nw_pmp_installed_date'),
            'warr_info' => $request->input('nw_pmp_warr_info'),
            'purch_date' => $request->input('nw_pmp_purch_date'),
            'purch_from' => $request->input('nw_pmp_purch_from'),
            'store_id' => $request->input('nw_pmp_store'),
        ];

        $pump->fill($new_pump_data);
    
        $pump->save();
    
        return redirect()->back()->with('success', 'Pump updated successfully!');

    }
}
