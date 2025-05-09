<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

use App\Models\Work_orders;
use App\Models\Service_calls;
use App\Models\User;
use App\Models\Fleets;
use App\Models\Ro_locations;
use Carbon\Carbon;
//use PDF;

class Work_ordersController extends Controller
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
        $prio = request('prio', '');
        $fltRole = request('role', '');
        $dateRange  = request('d_range', '');

        $fleet_all = Fleets::all();
        $technicians_all = User::whereIn('role', [4, 5])->whereNull('deleted')->orderBy('name')->get();

        $user = auth()->user();
        $auth_role = auth()->user()->role;
        
        if($auth_role < 6){
            $customers_all = User::where('role', '=', 6)->whereNull('deleted')->orderBy('name', 'asc')->get();
        } else {
            $stores = $user->stores->whereNull('deleted')->sortBy('name');
            $stores[] = $user;
            $customers_all = $stores;
        }

        if (request()->has('add')) {
            return view('dashboard', compact('fleet_all', 'customers_all', 'technicians_all'));
        }
        
        if (request()->has('edit')) {
            if (request()->filled('edit')) {

                $woId = request()->input('edit');
                    
                $work_order = Work_orders::find($woId);

                if( $auth_role < 5 || $work_order->customer_id == $user->id || $work_order->tech_id == $user->id) {
                    if( $auth_role == 6 && $work_order->status === 'Completed') {
                        return redirect()->back()->with('error', 'Access denied');
                    }
                    
                    return view('dashboard', compact('work_order', 'fleet_all', 'customers_all', 'technicians_all'));
                } else {
                    return abort(404);
                }

            }
            return redirect('dashboard/work-orders');
        }     
        
        if($auth_role < 5){
            $work_orders = Work_orders::query();
        } elseif ($auth_role == 5){
            $work_orders = Work_orders::where('tech_id', $user->id);
        } else {
            $user_id = $user->id;
            $storeIds = $user->stores->pluck('id');
            $storeIds->push($user_id);
            $work_orders = Work_orders::whereIn('customer_id', $storeIds);
        } 
        
        if($prio){
            $work_orders = $work_orders->where('priority', $prio);
        }
        
        if(request()->has('comp')){
            $work_orders = $work_orders->where('status', 'Completed')->orderBy('comp_date', 'desc')->get();
        } else {
            $work_orders = $work_orders->where('status', '!=', 'Completed')->orderBy('id', 'desc')->get();
        }
        
        $work_for = auth()->user()->work_for;
        if($work_for && $auth_role > 3 && $auth_role !== 6){
            if($work_for == 'AMTX'){
                $work_orders = $work_orders->reject(function ($wo) {
                    return $wo->customer->com_to_inv !== 'AMTX';
                });
            } elseif($work_for == 'PTS') {
                $work_orders = $work_orders->reject(function ($wo) {
                    return $wo->customer->com_to_inv !== 'Petro-Tank Solutions';
                });
            }
        }
        
        foreach($work_orders as $wo){
            if($wo->comment){
                $office_comnts = json_decode($wo->comment, true);
    
                if($office_comnts){
                    foreach ($office_comnts as &$cmnt) {
                        $cmnt[1] = Carbon::parse($cmnt[1])->format('m/d/Y h:i A');
                    }
                }
            
                $wo->comment = $office_comnts;
            }
            
            if($wo->description){
                $cus_comnts = json_decode($wo->description, true);
    
                if($cus_comnts){
                    foreach ($cus_comnts as &$cmnt) {
                        $cmnt[1] = Carbon::parse($cmnt[1])->format('m/d/Y h:i A');
                    }
                }
            
                $wo->description = $cus_comnts;
            }
        }

        $work_orders = paginateCollection($work_orders, $perPage, $page);

        return view('dashboard', compact('work_orders'));
        
    }

    //for work orders add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $rules = [
            'cu_id' => 'required',
        ];

        $request->validate($rules);

        $last_wo = Work_orders::orderBy('wo_number', 'desc')->first();
        
        if($last_wo){
            $wo_number = $last_wo->wo_number + 1;
        } else {
            $wo_number = '00000';
        }

        $latest_wo =  Work_orders::latest()->first();
        $new_wo_number = $latest_wo ? str_pad($latest_wo->id + 1, 5, '0', STR_PAD_LEFT) : '00001';

        if(auth()->user()->role == 6){
            $auth_user = auth()->user();
            $cu_id = $request->input('cu_id');
            if($auth_user->id != $cu_id && !$auth_user->stores()->where('id', $cu_id)->exists()){
                return abort(404);
            }

            $work_order_data = [
                'wo_number' => $new_wo_number,
                'customer_id' => $cu_id,
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'createdBy' => auth()->user()->id,
                'status' => 'Pending',
                'priority' => 'Medium',
            ];

        } else {

            $work_order_data = [
                'wo_number' => $new_wo_number,
                'customer_id' => $request->input('cu_id'),
                'status' => $request->input('status'),
                'tech_id' => $request->input('tech_id'),
                'fleet_id' => $request->input('fleet_id'),
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'priority' => $request->input('priority'),
                'createdBy' => auth()->user()->id,
                'invoiced' => $request->input('invoiced') ?? null,
            ];

        }
            
        $work_order = Work_orders::create($work_order_data);
        
        if($request->input('description')){
            $cus_comnts = $work_order->description ? json_decode($work_order->description, true) : [];
            $cus_comnts[] = [$request->input('description'), now()->toDateTimeString(), "Store"];
            $work_order->description = $cus_comnts;
        }
            
        if($request->input('comment')){
            $office_comnts = $work_order->comment ? json_decode($work_order->comment, true) : [];
            $office_comnts[] = [$request->input('comment'), now()->toDateTimeString(), "Office"];
            $work_order->comment = $office_comnts;
        }
        
        $work_order->save();
    
        return redirect()->back()->with('success', 'New work order added successfully!');

    }

    //for work order edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $wo_id = $request->input('wo_id');
        $work_order = Work_orders::find($wo_id); 

        $rules = [
            'cu_id' => 'required',
        ];

        $request->validate($rules);

        if(auth()->user()->role == 6){
            $store_ids = auth()->user()->stores->pluck('id');
            $store_ids->push(auth()->user()->id);
            
            if( !in_array($work_order->customer_id, $store_ids->toArray()) ){
                return abort(404);
            }
            
            if($request->input('description')){
                $cus_comnts = $work_order->description ? json_decode($work_order->description, true) : [];
                $cus_comnts[] = [$request->input('description'), now()->toDateTimeString(), "Store"];
                $work_order->description = $cus_comnts;
                $work_order->save();
            }
            
            return redirect()->back()->with('success', 'Work order updated successfully!');

        } else {
            
            $curr_status = $work_order->status;
            
            $new_work_order_data = [
                'customer_id' => $request->input('cu_id'),
                'status' => $request->input('status'),
                'tech_id' => $request->input('tech_id'),
                'fleet_id' => $request->input('fleet_id'),
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'priority' => $request->input('priority'),
                'invoiced' => $request->input('invoiced') ?? null,
            ];
            
            if($request->input('comment')){
                $office_comnts = $work_order->comment ? json_decode($work_order->comment, true) : [];
                $office_comnts[] = [$request->input('comment'), now()->toDateTimeString(), "Office"];
                $work_order->comment = $office_comnts;
            }

        }

        $work_order->fill($new_work_order_data);
        if($request->input('status') == 'Completed' && $curr_status == 'Pending'){
            $work_order->comp_date = now()->toDateString();
            $work_order->comp_time = now()->toTimeString()->format('H:i'); 
        }
    
        $work_order->save();
    
        return redirect()->back()->with('success', 'Work order updated successfully!');

    }

    // for delete
    public function delete($id)
    {
        if(auth()->user()->role != 1) {
            return redirect()->back()->with('error', 'Access denied');
        }
        
        $work_order = Work_orders::findOrFail($id);
        $work_order->service_calls()->delete();
        $work_order->delete();

        return redirect()->back()->with('success', 'Work order deleted successfully!');
    }

    // for pdf generator
    public function pdfGen()
    {
        
        if (!auth()->user()) {
            return redirect('login');
        }

        if(auth()->user()->role > 4) {
            return redirect()->back()->with('error', 'Access denied');
        }

        $perPage = request('per_page', 10);

        $type = request('type', '');
        $type = ( $type == 'equip' ? 'Equipment' : ( $type == 'f_tank' ? 'Fuel Tank' : ( $type == 'pump' ? 'Pump' : ( $type == 'general' ? 'General' : '' ))));
        $store = request('store', '');
        $s = request('s', '');
        $dateRange  = request('d_range', '');
        if($dateRange == '' && $perPage != -1){
            $dateRange = date('m-d-Y', strtotime('-30 days')) . ' - ' . date('m-d-Y');
        }

        $user = auth()->user();

        $req_store_name = ''; // for filter by store

        if (request()->filled('list') && request()->filled('type')) {
            $item_id =  request('list');
            $req_type = request('type');
            $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

            if ($user->role < 3){
                if($req_type == 'equip') {
                    $work_orders = Work_orders::where('equipment_id', '=', $item_id)->where('created_at', '>=', $thirtyDaysAgo)->get();
                } elseif ($req_type == 'f_tank') {
                    $work_orders = Work_orders::where('fuel_tank_id', '=', $item_id)->where('created_at', '>=', $thirtyDaysAgo)->get();
                } elseif ($req_type == 'pump') {
                    $work_orders = Work_orders::where('pump_id', '=', $item_id)->where('created_at', '>=', $thirtyDaysAgo)->get();
                } elseif ($req_type == 'general') {
                    $work_orders = Work_orders::where('type', '=', 'General')->where('created_at', '>=', $thirtyDaysAgo)->get();
                } elseif ($req_type == 'store') {

                    
                    $work_orders = Work_orders::where('store_id', '=', $item_id)->where('created_at', '>=', $thirtyDaysAgo)->get();

                    $req_store_name = Stores::find($item_id)->name;
                
                }

            } elseif ($user->role > 2) {

                $user_store_ids = $user->stores->pluck('id')->toArray();

                if($req_type == 'equip') {
                    $work_orders = Work_orders::where('equipment_id', $item_id)->whereIn('store_id', $user_store_ids)->where('created_at', '>=', $thirtyDaysAgo)->get();
                } elseif ($req_type == 'f_tank') {
                    $work_orders = Work_orders::where('fuel_tank_id', $item_id)->whereIn('store_id', $user_store_ids)->where('created_at', '>=', $thirtyDaysAgo)->get();
                } elseif ($req_type == 'pump') {
                    $work_orders = Work_orders::where('pump_id', $item_id)->whereIn('store_id', $user_store_ids)->where('created_at', '>=', $thirtyDaysAgo)->get();
                } elseif ($req_type == 'general') {
                    $work_orders = Work_orders::where('type', 'General')->whereIn('store_id', $user_store_ids)->where('created_at', '>=', $thirtyDaysAgo)->get();
                } elseif ($req_type == 'store') {

                    $work_orders = array();

                    foreach($user_store_ids as $own_store){
                        if($own_store == $item_id){
                            $work_orders = Work_orders::where('store_id', '=', $item_id)->where('created_at', '>=', $thirtyDaysAgo)->get();
                            $req_store_name = Stores::find($item_id)->name;
                            break;
                        }
                    }
                
                }

            }

            $data = [
                'filters' => date('m-d-Y') . ' - ' . date('m-d-Y', strtotime('-30 days')),
                'today' => date('m-d-Y'),
                'work_orders' => $work_orders
            ]; 
                
            $pdf = PDF::loadView('layouts.dashboard.work-orders.pdf', $data);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download('Work Orders Sheet - ' . date('m-d-Y') . '.pdf');

        }

        $work_orders = filterRecords(Work_orders::class, null, $type, $store, $s, null, $dateRange);
       
        $store_name = $store != '' ? Stores::find($store)->name : '';
        
        $filters = [
            'Item Type' => $type,
            'Store' => $store_name,
            'Searched keyword' => $s,
            'Date Range' => $dateRange,
        ];
        
        $filters = array_filter($filters);
        
        $filters = implode(', ', array_map(function($key, $value) {
            return $key . ' : ' . $value;
        }, array_keys($filters), $filters));
        
        if($filters == '') {
            $filters = 'All';
        }

        $data = [
            'filters' => $filters,
            'today' => date('m-d-Y'),
            'work_orders' => $work_orders
        ]; 
            
        $pdf = PDF::loadView('layouts.dashboard.work-orders.pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Work Orders Sheet - ' . date('m-d-Y') . '.pdf');

    }
    
    // For images - ajax
    function wo_images(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $role = auth()->user()->role;
        
        if ( request()->has('call_id') && request()->filled('call_id') ) {
            
            $call = Service_calls::find(request()->input('call_id'));
            
            $images = [];
            if($call->images){
                $images = array_merge($images, json_decode($call->images));
            }

            return response()->json(['images' => $images]);            

        }
        
        if ( request()->has('id') && request()->filled('id') ) {
            
            $wo_id = request()->input('id');
            $wo = Work_orders::find($wo_id);
            
            $service_calls = $wo->service_calls;
            $images = [];
            if($service_calls){
                foreach($service_calls as $sc){
                    if($sc->images){
                        $images = array_merge($images, json_decode($sc->images));
                    }
                }
            }

            return response()->json(['wo_number' => $wo->wo_number, 'images' => $images]);            

        }

        return abort(404);
    }

    // For API
    function pend_wo()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {
            $user = auth()->user();
            $pend_wo = $user->work_orders()->where('status', 'pending')->latest()->take(10)->get();
            foreach($pend_wo as $order){
                $order['store_name'] = $order->customer->name;
                $order['store_address'] = $order->customer->str_addr;
                $order->makeHidden('customer');
            }
    
            return $pend_wo;
    
        }
    
        return response()->json(['message' => 'Access Denied'], 401);
    }
    
    function work_orders()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {
            $user = auth()->user();
            $wos = $user->work_orders()->orderBy('updated_at', 'desc')->get();
            foreach($wos as $wo){
                $wo['store_name'] = $wo->customer->name;
                $wo['store_address'] = $wo->customer->str_addr;
                
                $wo['ro_loc_id'] = Ro_locations::where('cus_id', $wo->customer->id)->first()->id ?? null;
                
                if($wo->description){
                    $comment = $wo->comment ? json_decode($wo->comment, true) : [];
                    $description = $wo->description ? json_decode($wo->description, true) : [];
                    
                    $merged_comment = array_merge($comment, $description);
                    usort($merged_comment, function($a, $b) {
                        $dateA = strtotime($a[1]);
                        $dateB = strtotime($b[1]);
                        return $dateB - $dateA;
                    });
                    
                    $wo->comment = $merged_comment;
                }
                $wo->makeHidden('description');
                
                $wo->makeHidden('customer');
            }
    
            return $wos;
    
        }
    
        return response()->json(['message' => 'Access Denied'], 401);
    }

    function update_wo(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $user = auth()->user();
        
        if ( $user->role == 4 || $user->role == 5 ) {

            $user = auth()->user();
            $wo = Work_orders::find($request->input('id'));
            
            if(!$wo){
                return response()->json(['message' => 'Work Order Not Fount'], 404);
            }

            if($user->id != $wo->tech_id){
                return response()->json(['message' => 'Access Denied'], 401);
            }
            
            
            $wo->status = $request->input('status') == 'Completed' ? 'Completed' : 'Pending';
            if($request->input('status') == 'Completed'){
                $wo->comp_date = Carbon::now('America/Chicago')->toDateString();
                $wo->comp_time = Carbon::now('America/Chicago')->format('H:i'); 
            }
            $wo->updated_at = Carbon::now('America/Chicago');
            $wo->save();
            
            $st_date = $request->input('start_date') ?? null;
            $co_date = $request->input('end_date') ?? null;
            $comnt = $request->input('comment') ?? null;
            
            if($request->input('status') && !$st_date && !$co_date && !$comnt){
                $comnt = "This work order marked as  " . $request->input('status');
            }
            
            $img_arr = null;
            if($request->input('images')){
                
                $directoryPath = public_path("picture-uploads/service-calls/$wo->id/");
                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0755, true);
                }
                
                $last_rec = $wo->service_calls()->latest()->first();
                $next_id = $last_rec ? $last_rec->id + 1 : 1;
                    
                $files = $request->input('images');
                
                $img_no = 1;
                foreach($files as $file){
                    $file = base64_decode($file, true);
    
                    $file_name = $next_id . "_" . $img_no . ".png";
                    $new_file_path = $directoryPath . $file_name;
                            
                    file_put_contents($new_file_path, $file);
                    
                    $img_arr[] = "/picture-uploads/service-calls/$wo->id/$file_name";
                    $img_no++;
                }
                
            }
            
            if($st_date || $co_date || $comnt){
                $new_service_call = [
                    'wo_id' => $wo->id,
                    'tech_id' => $user->id,
                    'start_date' => $st_date,
                    'comment' => $comnt,
                    'images' => $img_arr ? json_encode($img_arr) : null,
                    'created_at' => Carbon::now('America/Chicago'),
                    'updated_at' => Carbon::now('America/Chicago')
                ];
                
                $service_call = Service_calls::forceCreate($new_service_call);
            }
    
            return $wo;
    
        }
    
        return response()->json(['message' => 'Access Denied'], 401);
    }
    
    function service_calls(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {
            
            $user = auth()->user();
            $wo = Work_orders::find($request->input('id'));
            if($wo){
                $cus_name = $wo->customer->name;
                $fac_id = $wo->customer->fac_id;
                $str_addr = $wo->customer->str_addr;
                
                $id = 1;
                
                $service_calls = $wo->service_calls;
                foreach($service_calls as $call){
                    $call['cus_name'] = $cus_name;
                    $call['cus_fac_id'] = $fac_id;
                    $call['str_address'] = $str_addr;
                    $call['tech_name'] = $call->technician->name;
                    $call->makeHidden('technician');
                    
                    $images = $call->images ? json_decode($call->images) : null;
                    if($images){
                        $links = [];
                        foreach($images as $img) {
                            $links[] = url('') . $img;
                        }
                        $call->images = $links;
                    } else {
                        $call->images = [];
                    }
                    
                    $id = $call->id;
                }
                
                $cus_com = $wo->description ? json_decode($wo->description) : [];
                $office_com = $wo->comment ? json_decode($wo->comment) : [];
                $comments = array_merge($cus_com, $office_com);
                foreach($comments as &$com){
                    $id++;
                    $com['id'] = $id;
                    $com['comment'] = $com[0];
                    $com['cus_name'] = $cus_name;
                    $com['cus_fac_id'] = $fac_id;
                    $com['str_address'] = $str_addr;
                    $com['tech_name'] = $com[2];
                    $com['images'] = [];
                    $com['created_at'] = (new \DateTime($com[1], new \DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.u\Z');
                }
                unset($com);
                return collect($service_calls)->merge($comments)->sortByDesc('created_at')->values();
                
            } else {
                $service_calls = [];
            }
    
            return $service_calls;
    
        }
    
        return response()->json(['message' => 'Access Denied'], 401);
    }
}