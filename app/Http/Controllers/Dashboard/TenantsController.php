<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tenants;

class TenantsController extends Controller
{
    //for tenant add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $rules = [
            'tnt_address' => 'required|string|max:300', 
            'store' => 'required',
        ];
        $rules['tnt_size'] = $request->input('tnt_size') ? 'max:20' : '';
        $rules['tnt_rent'] = $request->input('tnt_rent') ? 'max:20' : '';
        $rules['tnt_vacant_status'] = $request->input('tnt_vacant_status') ? 'string|max:20' : '';
        $rules['tnt_name'] = $request->input('tnt_name') ? 'string|max:60' : '';
        $rules['tnt_contact'] = $request->input('tnt_contact') ? 'string|max:20' : '';
        $rules['tnt_agreement_path'] = $request->file('tnt_agreement') ? 'mimes:pdf|max:20480' : '';
        $rules['tnt_insurance_path'] = $request->file('tnt_insurance') ? 'mimes:pdf|max:20480' : '';
        $rules['tnt_dl_path'] = $request->file('tnt_dl') ? 'mimes:pdf|max:20480' : '';
        
        $request->validate($rules);
    
        $new_tenant_data = [
            'address' => $request->input('tnt_address'),
            'size' => $request->input('tnt_size'),
            'rent' => $request->input('tnt_rent'),
            'vacant' => $request->input('tnt_vacant_status'),
            'store_id' => $request->input('store'),
            'name' => $request->input('tnt_name'),        
            'contact' => $request->input('tnt_contact'),
        ];
            
        $tenant = Tenants::create($new_tenant_data);

        if($tenant && $request->input('tnt_vacant_status') !== 'Yes') {
            $store_id = $tenant->store_id;
            $tnt_id = $tenant->id;
            
            if ($request->hasFile('tnt_agreement')) {
                
                $fileName = 'Lease agreement - ' . str_replace('#', '', $request->file('tnt_agreement')->getClientOriginalName());
    
                $request->file('tnt_agreement')->move(public_path('tenants') . "/{$store_id}/{$tnt_id}/", $fileName);
    
                $new_tenant_data['agreement_path'] = $store_id . '/' . $tnt_id . '/' . $fileName;
            }

            if ($request->hasFile('tnt_insurance')) {
                
                $fileName = 'Insurance - ' . str_replace('#', '', $request->file('tnt_insurance')->getClientOriginalName());
    
                $request->file('tnt_insurance')->move(public_path('tenants') . "/{$store_id}/{$tnt_id}/", $fileName);
    
                $new_tenant_data['insurance_path'] = $store_id . '/' . $tnt_id . '/' . $fileName;
            }

            if ($request->hasFile('tnt_dl')) {
                
                $fileName = 'DL - ' . str_replace('#', '', $request->file('tnt_dl')->getClientOriginalName());
    
                $request->file('tnt_dl')->move(public_path('tenants') . "/{$store_id}/{$tnt_id}/", $fileName);
    
                $new_tenant_data['dl_path'] = $store_id . '/' . $tnt_id . '/' . $fileName;
            }

            $tenant->fill($new_tenant_data);
    
            $tenant->save();
            
        }
    
        return redirect()->back()->with('success', 'New tenant added successfully!');

    }

    //for tenant edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
            
        $rules = [
            'nw_tnt_address' => 'required|string|max:300', 
            //'nw_tnt_store' => 'required',
            'tnt_id' => 'required',
        ];
        $rules['nw_tnt_size'] = $request->input('nw_tnt_size') ? 'max:20' : '';
        $rules['nw_tnt_rent'] = $request->input('nw_tnt_rent') ? 'max:20' : '';
        $rules['nw_tnt_vacant_status'] = $request->input('nw_tnt_vacant_status') ? 'string|max:20' : '';
        $rules['nw_tnt_name'] = $request->input('nw_tnt_name') ? 'string|max:60' : '';
        $rules['nw_tnt_contact'] = $request->input('nw_tnt_contact') ? 'string|max:20' : '';
        $rules['nw_tnt_agreement_path'] = $request->file('nw_tnt_agreement') ? 'mimes:pdf|max:20480' : '';
        $rules['nw_tnt_insurance_path'] = $request->file('nw_tnt_insurance') ? 'mimes:pdf|max:20480' : '';
        $rules['nw_tnt_dl_path'] = $request->file('nw_tnt_dl') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);

        $tnt_id = $request->input('tnt_id');
        $tenant = Tenants::find($tnt_id);
    
        $new_tenant_data = [
            'address' => $request->input('nw_tnt_address'),
            'size' => $request->input('nw_tnt_size'),
            'rent' => $request->input('nw_tnt_rent'),
            'vacant' => $request->input('nw_tnt_vacant_status'),
            //'store_id' => $request->input('nw_tnt_store'),
            'name' => $request->input('nw_tnt_name'),        
            'contact' => $request->input('nw_tnt_contact'),
        ];

        if($tenant && $request->input('nw_tnt_vacant_status') !== 'Yes') {
            $store_id = $tenant->store_id;
            
            if ($request->hasFile('nw_tnt_agreement')) {
                
                $fileName = 'Lease agreement - ' . str_replace('#', '', $request->file('nw_tnt_agreement')->getClientOriginalName());
    
                $request->file('nw_tnt_agreement')->move(public_path('tenants') . "/{$store_id}/{$tnt_id}/", $fileName);
    
                $new_tenant_data['agreement_path'] = $store_id . '/' . $tnt_id . '/' . $fileName;
            }

            if ($request->hasFile('nw_tnt_insurance')) {
                
                $fileName = 'Insurance - ' . str_replace('#', '', $request->file('nw_tnt_insurance')->getClientOriginalName());
    
                $request->file('nw_tnt_insurance')->move(public_path('tenants') . "/{$store_id}/{$tnt_id}/", $fileName);
    
                $new_tenant_data['insurance_path'] = $store_id . '/' . $tnt_id . '/' . $fileName;
            }

            if ($request->hasFile('nw_tnt_dl')) {
                
                $fileName = 'DL - ' . str_replace('#', '', $request->file('nw_tnt_dl')->getClientOriginalName());
    
                $request->file('nw_tnt_dl')->move(public_path('tenants') . "/{$store_id}/{$tnt_id}/", $fileName);
    
                $new_tenant_data['dl_path'] = $store_id . '/' . $tnt_id . '/' . $fileName;
            }

            $tenant->fill($new_tenant_data);
    
            $tenant->save();
            
        }

        $tenant->fill($new_tenant_data);
    
        $tenant->save();
    
        return redirect()->back()->with('success', 'Tenant updated successfully!');

    }
}
