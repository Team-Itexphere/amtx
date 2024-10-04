<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Equipments;

class EquipmentsController extends Controller
{
    //for equipment add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $rules = [
            'name' => 'required|string|max:300',
            'store' => 'required',
        ];
        $rules['purch_from'] = $request->input('purch_from') ? 'string|max:300' : '';
        $rules['purch_date'] = $request->input('purch_date') ? 'max:20' : '';
        $rules['cost'] = $request->input('cost') ? 'string|max:60' : '';
        $rules['warr_info'] = $request->input('warr_info') ? 'string|max:500' : '';
        $rules['serial'] = $request->input('serial') ? 'string|max:300' : '';
            
        $request->validate($rules);
    
        $new_equipment_data = [
            'name' => $request->input('name'),
            'purch_from' => $request->input('purch_from'),
            'purch_date' => $request->input('purch_date'),
            'cost' => $request->input('cost'),
            'warr_info' => $request->input('warr_info'),
            'serial' => $request->input('serial'),
            'store_id' => $request->input('store'),
        ];
            
        $equipment = Equipments::create($new_equipment_data);
    
        /*if ($equipment) {
            return response()->json(['message' => 'Equipment added successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to add equipment'], 500);
        }*/
    
        return redirect()->back()->with('success', 'New equipment added successfully!');

    }

    //for equipment edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $eq_id = $request->input('eq_id');
        $equipment = Equipments::find($eq_id);
            
        $rules = [
            'nw_name' => 'required|string|max:300',
        ];
        $rules['nw_purch_form'] = $request->input('nw_purch_form') ? 'string|max:300' : '';
        $rules['nw_purch_date'] = $request->input('nw_purch_date') ? 'max:20' : '';
        $rules['nw_cost'] = $request->input('nw_cost') ? 'string|max:60' : '';
        $rules['nw_warr_info'] = $request->input('nw_warr_info') ? 'string|max:500' : '';
        $rules['nw_serial'] = $request->input('nw_serial') ? 'string|max:300' : '';
            
        $request->validate($rules);
    
        $new_equipment_data = [
            'name' => $request->input('nw_name'),
            'purch_form' => $request->input('nw_purch_form'),
            'purch_date' => $request->input('nw_purch_date'),
            'warr_info' => $request->input('nw_warr_info'),
            'serial' => $request->input('nw_serial'),
            'store_id' => $request->input('nw_store'),
        ];

        if(auth()->user()->role < 4){
            $new_equipment_data['cost'] = $request->input('nw_cost');            
        }


        $equipment->fill($new_equipment_data);
    
        $equipment->save();
    
        return redirect()->back()->with('success', 'Equipment updated successfully!');

    }
}
