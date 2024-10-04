<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Stores;
use App\Models\Fleets;
use App\Models\Cus_notes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class EmployeesController extends Controller
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
        $stores = Stores::where('status', 'Complete');
        
        if ($stores) {
            if($role < 3){
                $stores = $stores->get();
            } else {
                $stores = auth()->user()->stores;
            }
        } else {
            $stores = array();
        }

        if (Route::is('my-profile')) {
            if($role > 4){
                return abort(404);
            }
            
            $user = auth()->user();
            return view('dashboard', compact('user', 'stores'));
        }
        
        if ( $role < 5 ) {
            
            if (request()->has('add')) {
                $fleets = Fleets::all();
                
                $parent_str = null;
                $other_stores = [];
                $user = null;
                $site_info = null;
                if (request()->has('parent') && request()->filled('parent')) {
                    $parent_str = User::find(request()->input('parent'));
                    
                    if($parent_str){
                        $other_stores = User::where('role', 6)->whereNull('deleted')->where('id', '!=', $parent_str->id)->get();
                
                        foreach($other_stores as $str){
                            if(request()->has('ref_store') && request()->input('ref_store') == $str->id){
                                $user = $str;
                                $site_info = $user->site_info ?? null;
                                break;
                            }
                        }
                    }
                }
                
                return view('dashboard', compact('fleets', 'parent_str', 'other_stores', 'user', 'site_info'));
            }
            
            if (request()->has('edit')) {
                if (request()->filled('edit')) {
                    $userId = request()->input('edit');
                    
                    $user = User::find($userId);
                    $fleets = Fleets::all();
                    
                    $users = $user->stores()->whereNull('deleted')->get();
                    $site_info = $user->site_info ?? null;

                    if ( $role < $user->role || auth()->user()->id == $userId || $role == 1 ) {
                        return view('dashboard', compact('user', 'fleets', 'users', 'site_info'));
                    }
                }
                return redirect('dashboard/employees');
            }
            
            $users = filterRecords(User::class, null, $type, $store, $s, $fltRole);
            
            $parent = null;
            if(request()->has('parent')){
                $parent = User::find(request()->input('parent'));
                $users[] = $parent;
            }
            $users = $users->sortBy('name');

            $users = paginateCollection($users, $perPage, $page);

            return view('dashboard', compact('users', 'parent'));

        } elseif ( $role == 6 && request()->has('parent') ) {
            
            $parent = auth()->user();
            $users = $parent->stores()->where('deleted', null)->get();
            $users[] = $parent;
            $users = paginateCollection($users, $perPage, $page);
            
            return view('dashboard', compact('users', 'parent'));
            
        }

        return redirect('login');
    }

    //for user switcher
    public function switch_to($id){
        if(Auth::check() && Auth::user()->role == 1){
            $auth_user = Auth::user();
            $req_user = User::find($id);

            if($req_user->role == 6){
                $auth_user->switch_as = $id;
                $auth_user->save();
                return redirect()->route('dashboard');
            }
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

        if ( $role < 5 ) {
            
            if($request->input('ref_store') && $request->input('parent')){
                $parent = User::find($request->input('parent'));
                $ref = User::find($request->input('parent'));
                
                if($ref && $ref->role == 6){
                    $parent->stores()->attach($request->input('ref_store'));
                
                    return redirect()->back()->with('success', 'Store linked successfully!');
                }
                
                return redirect()->back()->with('error', 'Failed to link store');
            }

            try {
                
                $rules = [
                    'role' => 'required|in:1,2,3,4,5,6',
                ];
                
                if($request->input('fac_id')){
                    $rules['fac_id'] = 'string|max:60|unique:users';
                }
                
                if($request->input('email')){
                    if(User::where('email', $request->input('email'))->first()){
                        return redirect()->back()->with('error', 'Email is already exist!');
                    }
                }
                
                if($request->input('login')){
                    $rules['email'] = 'string|max:60';
                    $rules['password'] = 'required|string|min:5';
                }
                
                $request->validate($rules);
        
                if($request->input('phone')) {
                    $country_code = config('app.country_code', 'Laravel');
                    $phone = $country_code . $request->input('phone');
                }
    
                $new_user_data = [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $phone ?? null,
                    'password' => Hash::make($request->input('password')),
                    'fleet_id' => $request->input('fleet_id'),
                    'com_name' => $request->input('com_name'),
                    'own_name' => $request->input('own_name'),
                    'str_addr' => $request->input('str_addr'),
                    'str_phone' => $request->input('str_phone'),
                    'cp_name' => $request->input('cp_name'),
                    'cp_phone' => $request->input('cp_phone'),
                    'own_email' => $request->input('own_email'),
                    'email_list' => $request->input('email_list'),
                    'fac_id' => $request->input('fac_id'),
                    'com_to_inv' => $request->input('com_to_inv'),
                    'cus_type' => $request->input('role') == 6 ? $request->input('cus_type') : null,
                    'login' => $request->input('login') ?? 0,
                    'rec_logs' => $request->input('rec_logs') ?? 0,
                ];
                
                if($role > $request->input('role')) {
                    return redirect()->back()->with('error', 'Access denied');
                } elseif ($role == 2 && $request->input('role') == 1) {
                    $new_user_data['role'] = 2;
                } elseif ($request->input('parent')) {
                    $new_user_data['role'] = 6;
                } else {
                    $new_user_data['role'] = $request->input('role');
                }
                
                $user = User::create($new_user_data);
                
                if($request->input('parent')){
                    $parent = User::find($request->input('parent'));
                    $parent->stores()->attach($user->id);
                }
                
                if($user && $user->role == 6){
                    
                    $new_site_info_data = [
                        'fu_brand'      => $request->input('fu_brand') ?? null,
                        'truck_stop'    => $request->input('truck_stop') ?? null,
                        'dis_brand'     => $request->input('dis_brand') ?? null,
                        'dis_model'     => $request->input('dis_model') ?? null,
                        'dis_sumps'     => $request->input('dis_sumps') ?? null,
                        'dis_type'      => $request->input('dis_type') ?? null,
                        'vents_count'   => $request->input('vents_count') ?? null,
                        'h_many_3_0'    => $request->input('h_many_3_0') ?? null,
                        'h_many_3_1'    => $request->input('h_many_3_1') ?? null,
                        'h_many_h_flows'=> $request->input('h_many_h_flows') ?? null,
                        'tanks_count'   => $request->input('tanks_count') ?? null,
                        'atg_brand'     => $request->input('atg_brand') ?? null,
                        'atg_sensors'   => $request->input('atg_sensors') ? json_encode($request->input('atg_sensors')) : null,
                        'relay_brand'   => $request->input('relay_brand') ?? null,
                        'pos_system'    => $request->input('pos_system') ?? null,
                        'lock'          => $request->input('info_lock') ?? 0,
                    ];
            
                    if ($user->site_info) {
                        $user->site_info->update($new_site_info_data);
                    } else {
                        $user->site_info()->create($new_site_info_data);
                    }
                    
                    $user->load('site_info');
                }
                
                $site_info = $user->site_info;
                if($site_info && $request->input('tanks')){
                    $tanks = json_decode($request->input('tanks'), true);
                    
                    foreach($tanks as $tank){
                        $new_tank = [
                            'tank_name'         => $tank['tank_name'] ?? null,
                            'fu_type'           => $tank['fu_type'] ?? null,
                            'size'              => $tank['size'] ?? null,
                            'diameter'          => $tank['diameter'] ?? null,
                            'material'          => $tank['material'] ?? null,
                            'sb_brand'          => $tank['sb_brand'] ?? null,
                            'wall_type'         => $tank['wall_type'] ?? null,
                            'drain'             => $tank['drain'] ?? null,
                            'h_many_g_bucket'   => $tank['h_many_g_bucket'] ?? null,
                            'in_denpth'         => $tank['in_denpth'] ?? null,
                            'overfill_prev'     => $tank['overfill_prev'] ?? null,
                            'vent_type'         => $tank['vent_type'] ?? null,
                            'stp_manf'          => $tank['stp_manf'] ?? null,
                            'leak_detector'     => $tank['leak_detector'] ?? null,
                            'stp_sumps'         => $tank['stp_sumps'] ?? null,
                            'stps_type'         => $tank['stp_sumps'] == 'Yes' && isset($tank['stps_type']) ? $tank['stps_type'] : null,
                        ];
                    
                        $site_info->site_info_tanks()->create($new_tank);
                    }
                }
                
                if($user->role == 6 && $user->parent_id){
                    return redirect()->back()->with('success', 'Store added successfully!');
                }
                
                if($user->role == 6){
                    return redirect()->back()->with('success', 'New customer created successfully!');
                }
                return redirect()->back()->with('success', 'New employee created successfully!');
                
            } catch (Exception $e) {
                
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
                
            }
        } else {
            return redirect('login');
        }
    }

    //for user edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role < 5 ) {
            try {
                
                $user_id = $request->input('u_id');
                $user = User::find($user_id);
                
                if ($role > $request->input('role') || $role > 3) {
                    return redirect('/dashboard/employees?edit='.$user_id)->with('error', 'Access denied');
                }
        
                $rules = [
                    'role' => 'required|in:1,2,3,4,5,6',
                ];
                $rules['fac_id'] = $request->input('fac_id') && $user->fac_id != $request->input('fac_id') ? 'string|max:60|unique:users' : '';
                
                if($request->input('email')){
                    $curr_urs = User::where('email', $request->input('email'))->first();
                    if($curr_urs && $curr_urs->id != $user_id){
                        return redirect()->back()->with('error', 'Email is already exist!');
                    }
                }
                
                if($request->input('login')){
                    $rules['email'] = $user->email != $request->input('email') ? 'required|string|email|max:50' : '';
                }
                
                $request->validate($rules);
        
                $user->name = $request->input('name');
                $user->email = $request->input('email');
    
                //$country_code = config('app.country_code', 'Laravel');            
                $user->phone = $request->input('phone');
        
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->input('password'));
                }
        
                $user->fleet_id     = $request->input('fleet_id');
                $user->com_name     = $request->input('com_name');
                $user->own_name     = $request->input('own_name');
                $user->str_addr     = $request->input('str_addr');
                $user->str_phone    = $request->input('str_phone');
                $user->cp_name      = $request->input('cp_name');
                $user->cp_phone     = $request->input('cp_phone');
                $user->own_email    = $request->input('own_email');
                $user->email_list   = $request->input('email_list');
                $user->fac_id       = $request->input('fac_id');
                $user->com_to_inv   = $request->input('com_to_inv');
                $user->cus_type     = $request->input('role') == 6 ? $request->input('cus_type') : null;
                $user->login        = $request->input('login') ?? 0;
                $user->rec_logs     = $request->input('rec_logs') ?? 0;
    
                if ( $role == 2 && $request->input('role') == 1 ) {
                    $user->role = 2;
                } else {
                    $user->role = $request->input('role');
                }
        
                $user->save();
                
                if($user->role == 6){
                    
                    $new_site_info_data = [
                        'fu_brand'      => $request->input('fu_brand') ?? null,
                        'truck_stop'    => $request->input('truck_stop') ?? null,
                        'dis_brand'     => $request->input('dis_brand') ?? null,
                        'dis_model'     => $request->input('dis_model') ?? null,
                        'dis_sumps'     => $request->input('dis_sumps') ?? null,
                        'dis_type'      => $request->input('dis_type') ?? null,
                        'vents_count'   => $request->input('vents_count') ?? null,
                        'h_many_3_0'    => $request->input('h_many_3_0') ?? null,
                        'h_many_3_1'    => $request->input('h_many_3_1') ?? null,
                        'h_many_h_flows'=> $request->input('h_many_h_flows') ?? null,
                        'tanks_count'   => $request->input('tanks_count') ?? null,
                        'atg_brand'     => $request->input('atg_brand') ?? null,
                        'atg_sensors'   => $request->input('atg_sensors') ? json_encode($request->input('atg_sensors')) : null,
                        'relay_brand'   => $request->input('relay_brand') ?? null,
                        'pos_system'    => $request->input('pos_system') ?? null,
                        'lock'          => $request->input('info_lock') ?? 0,
                    ];
            
                    if ($user->site_info) {
                        $user->site_info->update($new_site_info_data);
                    } else {
                        $user->site_info()->create($new_site_info_data);
                    }
                    
                }
                
                $site_info = $user->site_info;
                if($site_info && $request->input('tanks')){
                    $tanks = json_decode($request->input('tanks'), true);
                    
                    $site_info->site_info_tanks()->delete();
                    
                    foreach($tanks as $tank){
                        $new_tank = [
                            'tank_name'           => $tank['tank_name'] ?? null,
                            'fu_type'           => $tank['fu_type'] ?? null,
                            'size'              => $tank['size'] ?? null,
                            'diameter'          => $tank['diameter'] ?? null,
                            'material'          => $tank['material'] ?? null,
                            'sb_brand'          => $tank['sb_brand'] ?? null,
                            'wall_type'         => $tank['wall_type'] ?? null,
                            'drain'             => $tank['drain'] ?? null,
                            'h_many_g_bucket'   => $tank['h_many_g_bucket'] ?? null,
                            'in_denpth'         => $tank['in_denpth'] ?? null,
                            'overfill_prev'     => $tank['overfill_prev'] ?? null,
                            'vent_type'         => $tank['vent_type'] ?? null,
                            'stp_manf'          => $tank['stp_manf'] ?? null,
                            'leak_detector'     => $tank['leak_detector'] ?? null,
                            'stp_sumps'         => $tank['stp_sumps'] ?? null,
                            'stps_type'         => $tank['stp_sumps'] == 'Yes' && isset($tank['stps_type']) ? $tank['stps_type'] : null,
                        ];
                    
                        $site_info->site_info_tanks()->create($new_tank);
                    }
                }
        
                if($user->role == 6){
                    return redirect()->back()->with('success', 'Customer updated successfully!');
                }
                return redirect()->back()->with('success', 'Employee updated successfully!');
                
            } catch (QueryException $e) {
                
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
                
            } catch (Exception $e) {

                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
                
            }
        } else {
            return redirect('login');
        }
    }

    //for my profile
    public function my_profile(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $user = auth()->user();
        $role = auth()->user()->role;
        
        if($role > 4){
            return abort(404);
        }

    
        $rules = [
            'role' => 'required|in:1,2,3,4,5,6',
        ];
        $rules['email'] = $user->email != $request->input('email') ? 'required|string|email|max:50|unique:users' : '';
        $rules['fac_id'] = $request->input('fac_id') && $user->fac_id != $request->input('fac_id') ? 'string|max:60|unique:users' : '';
        /*$rules['address'] = $request->input('address') ? 'string|max:300' : '';
        $rules['id_num'] = $request->input('id_num') ? 'string|max:50' : '';
        $rules['ssn'] = $request->input('ssn') ? 'string|max:300' : '';
        $rules['pay'] = $request->input('pay') ? 'numeric' : '';
        $rules['lpg_date'] = $request->input('lpg_date') ? 'date' : '';
        $rules['hire_date'] = $request->input('hire_date') ? 'date' : '';
        $rules['password'] = $request->input('password') ? 'string|min:5' : '';*/
    
        $request->validate($rules);
    
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        //$country_code = config('app.country_code', 'Laravel');            
        $user->phone = $request->input('phone');
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
    
        $user->fleet_id = $request->input('fleet');
        $user->com_name = $request->input('com_name');
        $user->own_name = $request->input('own_name');
        $user->str_addr = $request->input('str_addr');
        $user->str_phone = $request->input('str_phone');
        $user->cp_name = $request->input('cp_name');
        $user->cp_phone = $request->input('cp_phone');
        $user->own_email = $request->input('own_email');
        $user->email_list = $request->input('email_list');
        $user->fac_id = $request->input('fac_id');
        $user->com_to_inv = $request->input('com_to_inv');

        if ( $role > $request->input('role') ) {
            $user->role = $role;
        } else {
            $user->role = $request->input('role');
        }
    
        $user->save();
    
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    //for user deactivate
    public function deactivate($id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role != 1) {
            return abort(404);
        }
        
        $user = User::find($id);

        if($user){
            $user->deleted = 'yes';
            $user->save();
        }
    
        return redirect()->back()->with('success', 'User deactivated successfully!');

    }
    
    //for user reactivate
    public function reactivate($id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role != 1) {
            return abort(404);
        }
        
        $user = User::find($id);

        if($user){
            $user->deleted = null;
            $user->save();
        }
    
        return redirect()->back()->with('success', 'User reactivated successfully!');

    }
    
    //for store detach
    public function detach_store(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role != 1 || !request()->has('id') || !request()->has('parent')) {
            return abort(404);
        }
        
        $store = User::find(request()->input('id'));
        $parent = User::find(request()->input('parent'));

        if($store && $parent && $store->id != $parent->id){
            $parent->stores()->detach($store->id);
        } else {
            return abort(404);
        }
    
        return redirect()->back()->with('success', 'Store detached successfully!');

    }
    
    
    // add customer notes
    function add_cus_note(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }
        
        $cus_id = $request->input('customer_id');
        $note = $request->input('note');
            
        if(!$cus_id || !$note){
            return redirect()->back()->with('error', 'Required fields not found');
        }
    
        $new_cus_note_data = [
            'cus_id' => $cus_id,
            'note' => $note,
            'status' => 'Pending'
        ];
    
        $ro_loc_note = Cus_notes::create($new_cus_note_data);
        return redirect()->back()->with('success', 'Note added successfully!');
    }
    
    // edit customer notes
    function edit_cus_note(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }
        
        $note_id = $request->input('note_id');
        //$cus_id = $request->input('cus_id');
        $note_text = $request->input('note');
            
        if(!$note_text){
            return redirect()->back()->with('error', 'Required fields not found');
        }
        
        $note = Cus_notes::find($note_id);
        $note->note = $note_text;
        
        if($request->input('com_check') && $request->input('com_check') == 1){
            $note->status = 'Completed';
        } else {
            $note->status = 'Pending';
        }
        
        $note->save();
    
        return redirect()->back()->with('success', 'Note edited successfully!');
    }

    // get customer notes for web - ajax
    function view_cus_notes(Request $request)
    {
        $perPage = request('per_page', 10);
        $page = request('page', 1);
        
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 5) {
            return abort(404);
        }
            
        $notes = Cus_notes::orderBy('created_at', 'desc')->get();
        if($notes){
            foreach($notes as $note){
                $note['date'] = $note->updated_at->format('m/d/Y');
            }
        }
        
        $notes = paginateCollection($notes, $perPage, $page);
        
        $customers = User::where('role', 6)->get();
        
        return view('dashboard', compact('notes', 'customers'));
    }



// For API (sanctum - old method)

    /*public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AppAuthToken')->plainTextToken;

            return response()->json(['AppAuthToken' => $token]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }*/

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
    
}