<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Utilities;

class UtilitiesController extends Controller
{
    //for utility add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $rules = [
            'ut_type' => 'required|string|max:60', 
            'store' => 'required',
            'ut_service_pro' => 'required',
        ];
        $rules['ut_service_pro'] = $request->input('ut_service_pro') ? 'string|max:60' : '';
        $rules['ut_avg_m_bill'] = $request->input('ut_avg_m_bill') ? 'max:20' : '';
        $rules['ut_end_date'] = $request->input('ut_end_date') ? 'max:20' : '';
        $rules['ut_acc_num'] = $request->input('ut_acc_num') ? 'string|max:60' : '';
        $rules['ut_note'] = $request->input('ut_note') ? 'max:600' : '';
        $rules['bill_doc'] = $request->file('bill_doc') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);
    
        $new_utilitie_data = [
            'type' => $request->input('ut_type'),
            'service_pro' => $request->input('ut_service_pro'),
            'avg_m_bill' => $request->input('ut_avg_m_bill'),
            'end_date' => $request->input('ut_end_date'),
            'acc_num' => $request->input('ut_acc_num'),
            'note' => $request->input('ut_note'),           
            'store_id' => $request->input('store'),
        ];

        if ($request->hasFile('bill_doc')) {
            $store_id = $request->input('store');
            $ut_name = $request->input('ut_service_pro');
            
            $customName = $ut_name .  ' - STR_' . $store_id;
            $fileName = $customName . ' - ' . str_replace('#', '', $request->file('bill_doc')->getClientOriginalName());

            $request->file('bill_doc')->move(public_path('bills') . "/{$store_id}/", $fileName);

            $new_utilitie_data['bill_path'] = $store_id . '/' . $fileName;
        }
            
        $Utilitie = Utilities::create($new_utilitie_data);
    
        return redirect()->back()->with('success', 'New utility added successfully!');

    }

    //for utility edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
            
        $rules = [
            'nw_ut_type' => 'required|string|max:60', 
            //'nw_ut_store' => 'required',
            'ut_id' => 'required',
            'nw_ut_service_pro' => 'required',
        ];
        $rules['nw_ut_service_pro'] = $request->input('nw_ut_service_pro') ? 'string|max:60' : '';
        $rules['nw_ut_avg_m_bill'] = $request->input('nw_ut_avg_m_bill') ? 'max:20' : '';
        $rules['nw_ut_end_date'] = $request->input('nw_ut_end_date') ? 'max:20' : '';
        $rules['nw_ut_acc_num'] = $request->input('nw_ut_acc_num') ? 'string|max:60' : '';
        $rules['nw_ut_note'] = $request->input('nw_ut_note') ? 'max:600' : '';
        $rules['nw_bill_doc'] = $request->file('nw_bill_doc') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);

        $ut_id = $request->input('ut_id');
        $store_utilitie = Utilities::find($ut_id);
    
        $new_utilitie_data = [
            'type' => $request->input('nw_ut_type'),
            'service_pro' => $request->input('nw_ut_service_pro'),
            'avg_m_bill' => $request->input('nw_ut_avg_m_bill'),
            'end_date' => $request->input('nw_ut_end_date'),
            'acc_num' => $request->input('nw_ut_acc_num'),
            'note' => $request->input('nw_ut_note'),           
            //'store_id' => $request->input('nw_ut_store'),
        ];

        if ($request->hasFile('nw_bill_doc')) {
            $store_id = $store_utilitie->store_id;
            $ut_name = $request->input('nw_ut_service_pro');
            
            $customName = $ut_name .  ' - STR_' . $store_id;
            $fileName = $customName . ' - ' . str_replace('#', '', $request->file('nw_bill_doc')->getClientOriginalName());

            $request->file('nw_bill_doc')->move(public_path('bills') . "/{$store_id}/", $fileName);

            $new_utilitie_data['bill_path'] = $store_id . '/' . $fileName;
        }

        $store_utilitie->fill($new_utilitie_data);
    
        $store_utilitie->save();
    
        return redirect()->back()->with('success', 'Utility updated successfully!');

    }
}
