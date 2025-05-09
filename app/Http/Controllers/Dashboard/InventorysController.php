<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Inventorys;
use App\Models\Fleets;

class InventorysController extends Controller
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
        
        if ( $role < 3 ) {
            $fleets = Fleets::all();
            
            if (request()->has('add')) {
                return view('dashboard', compact('fleets'));
            }
            
            if (request()->has('edit')) {
                if (request()->filled('edit')) {
                    $inventoryId = request()->input('edit');
                    
                    $inventory = Inventorys::find($inventoryId);

                    if ( $role < 3 ) {
                        return view('dashboard', compact('inventory', 'fleets'));
                    }
                }
                return redirect('dashboard/inventory');
            }

            //$fleets = filterRecords(Fleets::class, null, $type, null, $s, $fltRole);
            $inventorys = Inventorys::all();
            

            $inventorys = paginateCollection($inventorys, $perPage, $page);

            return view('dashboard', compact('inventorys'));

        }

        return abort(404);
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
        
        if ( $role < 3 ) {
        
            $rules = [
                'part_no' => 'required|max:30',
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
    
            $new_inventory_data = [
                'part_no' => $request->input('part_no'),
                'serial' => $request->input('serial'),
                'manufacturer' => $request->input('manufacturer'),
                'purchase_price' => $request->input('purchase_price'),
                'selling_price' => $request->input('selling_price'),
                'warranty' => $request->input('warranty'),
                'category' => $request->input('category'),
            ];

            $new_inventory_data['fleet_id'] = $request->input('fleet_id') != 0 ? $request->input('fleet_id') : null;
            
            $inventory = Inventorys::create($new_inventory_data);
    
            return redirect('/dashboard/inventory?add')->with('success', 'New inventory created successfully!');
        } else {
            return redirect('/dashboard/inventory?add')->with('error', 'Access denied');
        }
    }

    //for user edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role < 3 ) {
            $inventory_id = $request->input('in_id');
            $inventory = Inventorys::find($inventory_id);
    
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
    
            $inventory->part_no = $request->input('part_no');
            $inventory->serial = $request->input('serial');
            $inventory->manufacturer = $request->input('manufacturer');
            $inventory->purchase_price = $request->input('purchase_price');
            $inventory->selling_price = $request->input('selling_price');
            $inventory->warranty = $request->input('warranty');
            $inventory->category = $request->input('category');
            $inventory->fleet_id = $request->input('fleet_id');
            $inventory->fleet_id = $request->input('fleet_id') != 0 ? $request->input('fleet_id') : null;
    
            $inventory->save();
    
            return redirect('/dashboard/inventory?edit='.$inventory_id)->with('success', 'Inventory updated successfully!');
        } else {
            return redirect('/dashboard/inventory?edit='.$inventory_id)->with('error', 'Access denied');
        }
    }
}
