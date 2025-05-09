<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cus_sir_inv_docs;
use App\Models\User;

class Cus_sir_inv_docsController extends Controller
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
            
            $cus_sir_inv_docs = $customer->cus_sir_inv_docs;

            $cus_sir_inv_docs = paginateCollection($cus_sir_inv_docs, $perPage, $page);

            return view('dashboard', compact('cus_sir_inv_docs', 'customer'));            

        }

        return abort(404);
    }

    // for doc add
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
        $rules['doc'] = $request->file('doc') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);
    
        $new_doc_data = [
            'type' => $request->input('type'),
            'expire_date' => $request->input('expire_date'),
            'customer_id' => $request->input('id'),
        ];

        if ($request->hasFile('doc')) {
            $cus_id = $request->input('id');
            
            $fileName = $cus_id . ' - ' . str_replace('#', '', $request->file('doc')->getClientOriginalName());

            $request->file('doc')->move(public_path('customer') . "/cus-sir-inv-docs/{$cus_id}/", $fileName);

            $new_doc_data['doc_path'] = $cus_id . '/' . $fileName;
        }
            
        $cus_sir_inv_doc = Cus_sir_inv_docs::create($new_doc_data);
    
        return redirect()->back()->with('success', 'New document added successfully!');

    }

    // for doc edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 4 && auth()->user()->role != 6) {
            return abort(404);
        }
        
        $doc_id = $request->input('doc_id');
        $cus_sir_inv_doc = Cus_sir_inv_docs::find($doc_id);
        
        if (auth()->user()->role == 6 && in_array(auth()->user()->id, auth()->user()->stores->pluck('id')->toArray())) {
            return abort(404);
        }
            
        $rules = [
            'doc_id' => 'required|string|max:300',
        ];  
        $rules['nw_doc'] = $request->file('nw_doc') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);
    
        $new_doc_data = [
            'type' => $request->input('nw_type'),
            'expire_date' => $request->input('nw_expire_date'),
        ];

        if ($request->hasFile('nw_doc')) {
            $cus_id = $cus_sir_inv_doc->customer_id;

            $fileName = $cus_id . ' - ' . str_replace('#', '', $request->file('nw_doc')->getClientOriginalName());

            $request->file('nw_doc')->move(public_path('customer') . "/cus-sir-inv-docs/{$cus_id}/", $fileName);

            $new_doc_data['doc_path'] = $cus_id . '/' . $fileName;
        }

        $cus_sir_inv_doc->fill($new_doc_data);
    
        $cus_sir_inv_doc->save();
    
        return redirect()->back()->with('success', 'Document updated successfully!');

    }
    
    /*// for license delete
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
        
        if ( $role == 5 ) {

            $licenses = Cus_licenses::where('customer_id', $cus_id)->get();

            return $licenses;
            
        }

        return response()->json(['message' => 'Access denied'], 401);
    }*/
}
