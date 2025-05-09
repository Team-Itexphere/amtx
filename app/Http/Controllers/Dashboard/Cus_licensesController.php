<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cus_licenses;
use App\Models\User;

class Cus_licensesController extends Controller
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
            $auth_user = auth()->user();
            
            if($role == 6 && $auth_user->id != $user_Id && !$auth_user->stores()->where('id', $user_Id)->exists()){
                return abort(404);
            }

            $customer = User::find($user_Id);
            
            $cus_licenses = $customer->cus_licenses;

            $licenses = paginateCollection($cus_licenses, $perPage, $page);

            return view('dashboard', compact('licenses', 'customer'));            

        }

        return abort(404);
    }

    // for license add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 4 && auth()->user()->role != 6) {
            return abort(404);
        }
        
        if (auth()->user()->role == 6 && $request->input('id') != auth()->user()->id) {
            return abort(404);
        }
        
        $rules = [
            'id' => 'required',
        ];
        $rules['license_doc'] = $request->file('license_doc') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);
    
        $new_license_data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'expire_date' => $request->input('expire_date'),
            'agency' => $request->input('agency'),
            'customer_id' => $request->input('id'),
        ];

        if ($request->hasFile('license_doc')) {
            $cus_id = $request->input('id');
            
            $fileName = $cus_id . ' - ' . str_replace('#', '', $request->file('license_doc')->getClientOriginalName());

            $request->file('license_doc')->move(public_path('customer') . "/licenses/{$cus_id}/", $fileName);

            $new_license_data['doc_path'] = $cus_id . '/' . $fileName;
        }
            
        $license = Cus_licenses::create($new_license_data);

        /*if ($license) {
            return response()->json(['message' => 'License added successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to add license'], 500);
        }*/
    
        return redirect()->back()->with('success', 'New license added successfully!');

    }

    // for license edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }
        
        $li_id = $request->input('li_id');
        $cus_license = Cus_licenses::find($li_id);
        
        if (auth()->user()->role == 6 && in_array(auth()->user()->id, auth()->user()->stores->pluck('id')->toArray())) {
            return abort(404);
        }
            
        $rules = [
            'li_id' => 'required|string|max:300',
        ];  
        $rules['nw_license_doc'] = $request->file('nw_license_doc') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);
    
        $new_license_data = [
            'name' => $request->input('nw_name'),
            'type' => $request->input('nw_type'),
            'expire_date' => $request->input('nw_expire_date'),
            'agency' => $request->input('nw_agency'),
        ];

        if ($request->hasFile('nw_license_doc')) {
            $cus_id = $cus_license->customer_id;

            $fileName = $cus_id . ' - ' . str_replace('#', '', $request->file('nw_license_doc')->getClientOriginalName());

            $request->file('nw_license_doc')->move(public_path('customer') . "/licenses/{$cus_id}/", $fileName);

            $new_license_data['doc_path'] = $cus_id . '/' . $fileName;
        }

        $cus_license->fill($new_license_data);
    
        $cus_license->save();
    
        return redirect()->back()->with('success', 'License updated successfully!');

    }
    
    // for license delete
    public function delete_li($id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role != 1) {
            return abort(404);
        }
        
        $cus_license = Cus_licenses::find($id);

        if($cus_license){
            $cus_license->delete();
        }
    
        return redirect()->back()->with('success', 'License deleted successfully!');

    }
    
    
    // For API
    function li_list($cus_id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {

            $licenses = Cus_licenses::where('customer_id', $cus_id)->get();

            return $licenses;
            
        }

        return response()->json(['message' => 'Access denied'], 401);
    }
}
