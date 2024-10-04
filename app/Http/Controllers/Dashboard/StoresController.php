<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Stores;

class StoresController extends Controller
{
    //views for par
    public function index()
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $user = auth()->user();
        
        $perPage = request('per_page', 10);
        $page = request('page', 1);
        $type = request('type', '');
        $type = ( $type == 'equip' ? 'Equipment' : ( $type == 'f_tank' ? 'Fuel Tank' : ( $type == 'pump' ? 'Pump' : ( $type == 'general' ? 'General' : '' ))));
        $store = request('store', '');
        $s = request('s', '');
            
        if (request()->has('add')) {
            if ( $user->role < 3 ) {
                return view('dashboard');
            } else {
                return abort(404);
            }
        }

        if (request()->has('view')) {
            if (request()->filled('view')) {
                $storeId = request()->input('view');
                
                $store = Stores::find($storeId);
                $stores_all = Stores::where('status', 'Complete')->get();
            
                if ($store) {

                    if( !$store->users->contains($user) && $user->role > 2  ){
                        return abort(404);
                    }

                    return view('dashboard', compact('store', 'stores_all'));
                }
            }
            return redirect('dashboard/stores');
        }
            
        if (request()->has('edit')) {
            if (request()->filled('edit')) {
                
                if ($user->role > 2) {
                    return abort(404);
                }

                $storeId = request()->input('edit');
                
                $store = Stores::find($storeId);
            
                if ($store) {
                    return view('dashboard', compact('store'));
                }
            }
            return redirect('dashboard/stores');
        }

        if (request()->has('list') && request()->has('inactive') && $user->role > 2) {
            return abort(404);
        }

        $stores = filterRecords(Stores::class, null, $type, $store, $s);

        $stores = paginateCollection($stores, $perPage, $page);

        return view('dashboard', compact('stores'));
    }

    //for store add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $curr_user = auth()->user();

        if ($curr_user->role > 2) {
            return abort(404);
        }
        
        $rules = [
            'name' => 'required|string|max:120',
        ];
        $rules['address'] = $request->input('address') ? 'string|max:300' : '';
        $rules['phone'] = $request->input('phone') ? 'string|max:30' : '';
        $rules['fax'] = $request->input('fax') ? 'string|max:30' : '';
        $rules['size'] = $request->input('size') ? 'string|max:30' : '';
        $rules['status'] = $request->input('status') ? 'string|max:60' : '';
        $rules['expenses'] = $request->input('expenses') ? 'max:1000' : '';
        $rules['blue_print'] = $request->file('blue_print') ? 'mimes:pdf|max:20480' : '';
        $rules['doc_1'] = $request->file('doc_1') ? 'mimes:pdf|max:20480' : '';
        $rules['doc_2'] = $request->file('doc_2') ? 'mimes:pdf|max:20480' : '';
        $rules['llc'] = $request->input('llc') ? 'string|max:60' : '';
        $rules['ein'] = $request->input('ein') ? 'string|max:60' : '';
            
        $request->validate($rules);

        if( $request->input('expenses') && $request->input('expenses') == '[]' ){            
            $expenses = null;            
        } else {
            $expenses = $request->input('expenses');  
        }
    
        $new_store_data = [
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'fax' => $request->input('fax'),
            'size' => $request->input('size'),
            'status' => $request->input('status'),
            'expenses' => $expenses,
            'llc' => $request->input('llc'),
            'ein' => $request->input('ein'),
        ];
            
        $store = Stores::create($new_store_data);

        if($store) {

            $store_id = $store->id;

            if ($request->hasFile('blue_print')) {
                $fileName = 'Blue Print - ' . $request->file('blue_print')->getClientOriginalName();
    
                $request->file('blue_print')->move(public_path('store-docs') . "/{$store_id}/", $fileName);
    
                $store->blue_print_path = $store_id . '/' . $fileName;
            }

            if ($request->hasFile('doc_1')) {
                $fileName = 'Doc 1 - ' . $request->file('doc_1')->getClientOriginalName();
    
                $request->file('doc_1')->move(public_path('store-docs') . "/{$store_id}/", $fileName);
    
                $store->doc_1_path = $store_id . '/' . $fileName;
            }

            if ($request->hasFile('doc_2')) {
                $fileName = 'Doc 2 - ' . $request->file('doc_2')->getClientOriginalName();
    
                $request->file('doc_2')->move(public_path('store-docs') . "/{$store_id}/", $fileName);
    
                $store->doc_2_path = $store_id . '/' . $fileName;
            }
        }
    
        $store->save();
    
        return redirect('/dashboard/stores?add')->with('success', 'New store created successfully!');

    }

    //for store edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $curr_user = auth()->user();
        
        if ($curr_user->role > 2) {
            return abort(404);
        }

        $store_id = $request->input('st_id');
        $store = Stores::find($store_id);
        $user = auth()->user();

        if (auth()->user()->role > 2 && !$user->stores->contains($store)) {
            return redirect()->back()->with('error', 'Access denied');
        }
            
        $rules = [
            'name' => 'required|string|max:120',
        ];
        $rules['address'] = $request->input('address') ? 'string|max:300' : '';
        $rules['phone'] = $request->input('phone') ? 'string|max:30' : '';
        $rules['fax'] = $request->input('fax') ? 'string|max:30' : '';
        $rules['size'] = $request->input('size') ? 'string|max:30' : '';
        $rules['status'] = $request->input('status') ? 'string|max:60' : '';
        $rules['expenses'] = $request->input('expenses') ? 'max:1000' : '';
        $rules['blue_print'] = $request->file('blue_print') ? 'mimes:pdf|max:20480' : '';
        $rules['doc_1'] = $request->file('doc_1') ? 'mimes:pdf|max:20480' : '';
        $rules['doc_2'] = $request->file('doc_2') ? 'mimes:pdf|max:20480' : '';
        $rules['llc'] = $request->input('llc') ? 'string|max:60' : '';
        $rules['ein'] = $request->input('ein') ? 'string|max:60' : '';
    
        $request->validate($rules);
    
        $new_store_data = [
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'fax' => $request->input('fax'),
            'size' => $request->input('size'),
            'status' => $request->input('status'),
            'llc' => $request->input('llc'),
            'ein' => $request->input('ein'),
        ];

        if( $request->input('expenses') && $request->input('expenses') != '[]' ){            
            $new_store_data['expenses'] = $request->input('expenses');          
        }

        if ($request->hasFile('blue_print')) {
            $fileName = 'Blue Print - ' . $request->file('blue_print')->getClientOriginalName();

            $request->file('blue_print')->move(public_path('store-docs') . "/{$store_id}/", $fileName);

            $new_store_data['blue_print_path'] = $store_id . '/' . $fileName;
        }

        if ($request->hasFile('doc_1')) {
            $fileName = 'Doc 1 - ' . $request->file('doc_1')->getClientOriginalName();

            $request->file('doc_1')->move(public_path('store-docs') . "/{$store_id}/", $fileName);

            $new_store_data['doc_1_path'] = $store_id . '/' . $fileName;
        }

        if ($request->hasFile('doc_2')) {
            $fileName = 'Doc 2 - ' . $request->file('doc_2')->getClientOriginalName();

            $request->file('doc_2')->move(public_path('store-docs') . "/{$store_id}/", $fileName);

            $new_store_data['doc_2_path'] = $store_id . '/' . $fileName;
        }

        $store->fill($new_store_data);
    
        $store->save();
    
        return redirect('/dashboard/stores?edit='.$store_id)->with('success', 'Store updated successfully!');

    }
}
