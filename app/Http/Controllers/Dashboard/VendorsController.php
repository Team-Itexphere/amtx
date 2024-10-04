<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vendors;

class VendorsController extends Controller
{
    //for vendor add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $rules = [
            'vn_name' => 'required|string|max:60',
            'store' => 'required',
        ];
        $rules['vn_rep_name'] = $request->input('vn_rep_name') ? 'string|max:60' : '';
        $rules['vn_phone'] = $request->input('vn_phone') ? 'string|max:20' : '';
        $rules['vn_delivery'] = $request->input('vn_delivery') ? 'string|max:60' : '';
        $rules['vn_payment_type'] = $request->input('vn_payment_type') ? 'string|max:60' : '';
        $rules['vn_acc_num'] = $request->input('vn_acc_num') ? 'string|max:60' : '';
        
            
        $request->validate($rules);
    
        $vendor_data = [
            'name' => $request->input('vn_name'),
            'rep_name' => $request->input('vn_rep_name'),
            'phone' => $request->input('vn_phone'),
            'delivery' => $request->input('vn_delivery'),
            'payment_type' => $request->input('vn_payment_type'),
            'acc_num' => $request->input('vn_acc_num'),
            'store_id' => $request->input('store'),
        ];
            
        $vendor = Vendors::create($vendor_data);
    
        return redirect()->back()->with('success', 'New vendor added successfully!');

    }

    //for vendor edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $vn_id = $request->input('vn_id');
        $vendor = Vendors::find($vn_id);
            
        $rules = [
            'nw_vn_name' => 'required|string|max:60',
            //'nw_vn_store' => 'required',
        ];
        $rules['nw_vn_rep_name'] = $request->input('nw_vn_rep_name') ? 'string|max:60' : '';
        $rules['nw_vn_phone'] = $request->input('nw_vn_phone') ? 'string|max:20' : '';
        $rules['nw_vn_delivery'] = $request->input('nw_vn_delivery') ? 'string|max:60' : '';
        $rules['nw_vn_payment_type'] = $request->input('nw_vn_payment_type') ? 'string|max:60' : '';
        $rules['nw_vn_acc_num'] = $request->input('nw_vn_acc_num') ? 'string|max:60' : '';
            
        $request->validate($rules);
    
        $new_vendor_data = [
            'name' => $request->input('nw_vn_name'),
            'rep_name' => $request->input('nw_vn_rep_name'),
            'phone' => $request->input('nw_vn_phone'),
            'delivery' => $request->input('nw_vn_delivery'),
            'payment_type' => $request->input('nw_vn_payment_type'),
            'acc_num' => $request->input('nw_vn_acc_num'),
            //'store_id' => $request->input('nw_vn_store'),
        ];

        $vendor->fill($new_vendor_data);
    
        $vendor->save();
    
        return redirect()->back()->with('success', 'Vendor updated successfully!');

    }
}
