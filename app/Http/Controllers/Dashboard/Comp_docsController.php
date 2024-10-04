<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; 

use App\Models\Comp_docs;
use App\Models\User;

class Comp_docsController extends Controller
{
    // doc types
    public function comp_doc_types($id=null)
    {
        $types = array(
            1 => "Annual Line & Leak Detector Test",
            2 => "Stage 1 Test",
            3 => "Overfill Prevention Verification",
            4 => "Cathodic Protection Test (CP)",
            5 => "30-Day Spill Bucket Inspection Log",
            6 => "Annual Sump Inspection",
            7 => "Walkthrough Inspection",
            8 => "RP1200 Appendix C 3 Spill Bucket Test",
            9 => "RP1200 Appendix C 4 Sump Hydrostatic Test",
            10 => "RP1200 Appendix C 5 Overfill Test",
            11 => "RP1200 Appendix C 7 ATG Test",
            12 => "RP1200 Appendix C 8 Liquid Sensor Test",
            13 => "60-Day Rectifier Log Form",
            14 => "Tank Verification",
            15 => "HMI Application",
            16 => "TCEQ Registration Form",
            17 => "Texas Class C Form",
            18 => "A+B License",
            );
        
        if($id){
            return $types[$id];
        } else {
            return $types;
        }
        
    }
    
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
            
            $comp_docs = $customer->comp_docs;
            $doc_types = $this->comp_doc_types();

            $docs = paginateCollection($comp_docs, $perPage, $page);

            return view('dashboard', compact('docs', 'customer', 'doc_types'));            

        }

        return abort(404);
    }

    //for doc add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 4) {
            return abort(404);
        }
        
        $rules = [
            'name' => 'required|int|max:5', 
            'id' => 'required',
        ];
        $rules['doc'] = $request->file('doc') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);
    
        $new_doc_data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'expire_date' => $request->input('expire_date'),
            'customer_id' => $request->input('id'),
        ];

        if ($request->hasFile('doc')) {
            $cus_id = $request->input('id');
            
            $fileName = $cus_id . ' - ' . str_replace('#', '', $request->file('doc')->getClientOriginalName());

            $request->file('doc')->move(public_path('customer') . "/docs/{$cus_id}/", $fileName);

            $new_doc_data['doc_path'] = $cus_id . '/' . $fileName;
        }
            
        $doc = Comp_docs::create($new_doc_data);

        /*if ($doc) {
            return response()->json(['message' => 'Document added successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to add Document'], 500);
        }*/
    
        return redirect()->back()->with('success', 'New document added successfully!');

    }

    //for doc edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 4) {
            return abort(404);
        }
        
        $doc_id = $request->input('doc_id');
        $comp_doc = Comp_docs::find($doc_id);
            
        $rules = [
            'nw_name' => 'required|int|max:5',
            'doc_id' => 'required|string|max:300',
        ];  
        $rules['nw_doc'] = $request->file('nw_doc') ? 'mimes:pdf|max:20480' : ''; 
        
        $request->validate($rules);
    
        $new_doc_data = [
            'name' => $request->input('nw_name'),
            'type' => $request->input('nw_type'),
            'expire_date' => $request->input('nw_expire_date'),
        ];

        if ($request->hasFile('nw_doc')) {
            $cus_id = $comp_doc->customer_id;

            $fileName = $cus_id . ' - ' . str_replace('#', '', $request->file('nw_doc')->getClientOriginalName());

            $request->file('nw_doc')->move(public_path('customer') . "/docs/{$cus_id}/", $fileName);

            $new_doc_data['doc_path'] = $cus_id . '/' . $fileName;
        }

        $comp_doc->fill($new_doc_data);
    
        $comp_doc->save();
    
        return redirect()->back()->with('success', 'Document updated successfully!');

    }

    // for doc delete
    public function delete_cd($id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role != 1) {
            return abort(404);
        }
        
        $cd = Comp_docs::find($id);

        if($cd){
            $doc_path = public_path('customer') . "/docs/" . $cd->doc_path;
            if (File::exists($doc_path)) {
                File::delete($doc_path);
            }
            $cd->delete();
        }
    
        return redirect()->back()->with('success', 'Document deleted successfully!');

    }
}
