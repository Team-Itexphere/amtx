<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Routes;
use App\Models\Route_lists;
use App\Models\Ro_locations;
use App\Models\User;
use App\Models\Testings;

class routesController extends Controller
{
    //views for par
    public function index()
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $perPage = request('per_page', 10);
        $page = request('page', 1);

        $status_flt = request('status', '');
        $month_flt  = request('month', '');
        
        $role = auth()->user()->role;
        
        if ( $role < 4 ) {

            if (request()->has('view') && request()->filled('view')) {
                $route_Id = request()->input('view');

                $route = Routes::find($route_Id);
                $loc_count = $route->ro_locations->count();
                $loc_rate_total = $route->ro_locations->sum('amount');

                if($loc_count > 0){
                    $locations = $route->ro_locations->toArray();
        
                    foreach($locations as &$location){
                        $customer = User::find($location['cus_id']);
                        if ($customer) {
                            $location['cus_name'] = $customer->name;
                            $location['cus_fac_id'] = $customer->fac_id;
                        } else {
                            $location['cus_name'] = 'Unknown';
                        }
                    }
                } else {
                    $locations = array();
                }
                    
                return view('dashboard', compact('route', 'locations', 'loc_count', 'loc_rate_total'));
            }

            if (request()->has('view_rl') && request()->filled('view_rl')) {
                $ro_list_Id = request()->input('view_rl');

                $route_list = Route_lists::find($ro_list_Id);
                $ro_locations = $route_list->route->ro_locations;
                    
                return view('dashboard', compact('route_list', 'ro_locations'));
            }

            if (request()->has('route_lists')) {
                $routes = Routes::where('deleted', null)->orderBy('created_at', 'desc')->get();

                $route_lists = Route_lists::with('route');
                if($status_flt == 'completed'){

                    $route_lists = $route_lists->where('status', 'completed');

                } else {
                    
                    $route_lists = $route_lists->where('status', 'pending');

                }
                
                if($month_flt){
                    $route_lists = $route_lists->whereYear('start_date', date('Y', strtotime($month_flt)))->whereMonth('start_date', date('m', strtotime($month_flt)));
                }
                
                $route_lists = $route_lists->get();

                $route_lists = $route_lists->sortByDesc(function($routeList) {
                    return $routeList->start_date;
                });
                
                if ($status_flt == 'assigned'){
                    
                    foreach ($route_lists as $key => $r_list) {
                        if (!$r_list->tech_id) {
                            unset($route_lists[$key]);
                        }
                    }
                    
                } elseif ($status_flt == 'accepted') {
                    
                    foreach ($route_lists as $key => $r_list) {
                        if (count($r_list->testings) == 0) {
                            unset($route_lists[$key]);
                        }
                    }
                    
                } elseif ($status_flt == 'pending') {
                    
                    foreach ($route_lists as $key => $r_list) {
                        if ($r_list->tech_id) {
                            unset($route_lists[$key]);
                        }
                    }
                    
                }
                
                $route_lists = paginateCollection($route_lists, $perPage, $page);

                $technicians_all = User::where('role', 5)->get();
                    
                $customers_all = User::where('role', 6)->get();

                return view('dashboard', compact('route_lists', 'routes', 'customers_all', 'technicians_all'));
            }

            if (request()->has('add')) {
                $customers_all = User::where('role', 6)->get();

                return view('dashboard', compact('customers_all'));
            }
            
            if (request()->has('edit') && request()->filled('edit')) {
                $route_Id = request()->input('edit');

                $route = Routes::find($route_Id);
                    
                $customers_all = User::where('role', 6)->get();

                return view('dashboard', compact('route', 'customers_all'));
            }

            $routes = Routes::whereNull('deleted');

            $cus_type  = request('cus_type', '');
            if($cus_type){
                $routes = $routes->whereHas('ro_locations', function($query) use ($cus_type) {
                    $query->whereHas('customer', function($q) use ($cus_type) {
                        $q->where('com_to_inv', $cus_type);
                    })->orderBy('id')->limit(1); 
                });
            }

            $routes = $routes->orderByRaw('CAST(num AS UNSIGNED) ASC')->get();

            $routes = paginateCollection($routes, $perPage, $page);

            return view('dashboard', compact('routes'));

        }

        return abort(404);
    }

    //for route add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }

        $new_route_data = [
            'num' => $request->input('num'),
            'name' => $request->input('name'),
            'insp_type' => $request->input('insp_type'),
            'status' => 'pending',
        ];

        $route = Routes::create($new_route_data);

        $locations = $request->input('locations');
        if($locations){

            $locations = json_decode($locations, true);

            foreach($locations as $location){
                Ro_locations::create([
                    'route_id' => $route->id,
                    'cus_id' => $location['cus_id'],
                    'amount' => $location['amount'],
                ]);
            }

        }
    
        return redirect()->back()->with('success', 'New route added successfully!');

    }

    //for route edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }
        
        $ro_id = $request->input('ro_id');
        $route = Routes::find($ro_id);

        $new_route_data = [
            'name' => $request->input('name'),
            'num' => $request->input('num'),
            'insp_type' => $request->input('insp_type'),
        ];

        $route->fill($new_route_data);    
        $route->save();

        $locations = $request->input('locations');
        if($locations){

            $locations = json_decode($locations, true);

            try {
                $deletedRows = Ro_locations::where('route_id', $route->id)->delete();
            } catch (\Exception $e) {
                return redirect()->back()->with('success', 'Route updated successfully! (Locations won\'t be updated on this route)');
            }

            foreach($locations as $location){
                Ro_locations::create([
                    'route_id' => $route->id,
                    'cus_id' => $location['cus_id'],
                    'amount' => $location['amount'],
                ]);
            }

        }
    
        return redirect()->back()->with('success', 'Route updated successfully!');

    }
    
    //for route_lists add
    public function add_rl(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }

        $new_route_lists_data = [
            'route_id' => $request->input('route_id'),
            'tech_id' => $request->input('tech_id') != 0 ? $request->input('tech_id') : null,
            'start_date' => $request->input('start_date'),
            'status' => 'pending',
        ];

        $route = Route_lists::create($new_route_lists_data);
    
        return redirect()->back()->with('success', 'Assigned successfully!');

    }

    //for route_lists edit
    public function edit_rl(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }
        
        $ro_id = $request->input('rl_id');
        $route = Route_lists::find($ro_id);

        $new_route_lists_data = [
            'route_id' => $request->input('nw_route_id'),
            'tech_id' => $request->input('nw_tech_id') != 0 ? $request->input('nw_tech_id') : null,
            'start_date' => $request->input('nw_start_date') ?? null,
        ];

        $route->fill($new_route_lists_data);    
        $route->save();
    
        return redirect()->back()->with('success', 'Route list updated successfully!');

    }
    
    //for route_lists unassign
    public function unassign(Request $request, $id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }
        
        $route_list = Route_lists::find($id);
        $tech_name = $route_list->technician->name;
        $route_name = $route_list->route->name ?? $route_list->route->num;
        $route_list->tech_id = null;    
        $route_list->save();
    
        return redirect()->back()->with('success', $tech_name . ' unsigned from ' . $route_name . ' successfully!');

    }
    
    //for route deactivate
    public function deactivate($id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        if (auth()->user()->role > 3) {
            return abort(404);
        }
        
        $route = Routes::find($id);

        if($route){
            $route->deleted = 'yes';
            $route->save();
        }
    
        return redirect()->back()->with('success', 'Route deactivated successfully!');

    }
    

    // For API
    function list(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {

            if ($request->has('date')) {

                /*$today_locations = [];*/

                $req_date = date('Y-m-d', strtotime($request->input('date')));

                $user_id = auth()->user()->id;

                $route_lists = Route_lists::where('tech_id', $user_id)->where('start_date', $req_date)->whereHas('route', function ($query) {
                                    $query->whereNull('deleted');
                                })->get();
                                
                foreach($route_lists as $list){
                    $list->no = $list->route->num;
                    $list->name = $list->route->name;
                    $list->str_count = $list->route->ro_locations()->count();
                }

                /*if($route_lists){

                    foreach($route_lists as $r_list){
                        
                        if($r_list->route->ro_locations->count() == 0){
                            continue;
                        }

                        $locations = $r_list->route->ro_locations->toArray();
                        
                        foreach($locations as &$location){
                            $customer = User::find($location['cus_id']);
                            $testing = Testings::where('ro_loc_id', $location['id'])->whereDate('created_at', $req_date)->first();
                            if ($customer) {
                                $location['cus_name'] = $customer->name;
                                $location['cus_fac_id'] = $customer->fac_id;
                            } else {
                                $location['cus_name'] = 'Unknown';
                            }
                                
                            if($testing){
                                $location['status'] = $testing->status;
                            } else {
                                $location['status'] = 'pending';
                            }
                            
                            $location['list_id'] = $r_list->id;
                                
                            $today_locations[0][] = $location;
                                
                        }                    

                    }

                }

                return $today_locations;*/
                return $route_lists;

            }

            return auth()->user()->route_lists;
            
        }

        return response()->json(['message' => 'Date is required'], 400);
    }

    function locations($id)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {
    
            $route_list = Route_lists::find($id);
    
            if($route_list && $route_list->tech_id != auth()->user()->id){
                return response()->json(['message' => 'Access Denied'], 401);
            }
    
            if($route_list && $route_list->route->ro_locations){
                $locations = json_decode($route_list->route->ro_locations, true);
        
                foreach($locations as &$location){
                    $location['list_id'] = $route_list->id;
                    $customer = User::find($location['cus_id']);
                    if ($customer) {
                        $location['cus_name'] = $customer->name;
                        $location['cus_fac_id'] = $customer->fac_id;
                        
                        $location['notes'] = [];
                        $notes = $customer->cus_notes()->where('status', '!=', 'Completed')->get();
                        if($notes){
                            $location['notes'] = $notes;
                        }
                    } else {
                        $location['cus_name'] = 'Unknown';
                    }
                    
                    $current_test = Testings::where('cus_id', $customer->id)->where('route_list_id', $route_list->id)->first();
                    if($current_test){
                        $location['status'] = $current_test->status;
                    } else {
                        $location['status'] = 'pending';
                    }
                    $location['customer'] = $customer;
                    $location['route_no'] = $route_list->route->num;
                    
                }
            } else {
                $locations = array();
            }
    
            return $locations;
    
        }
    
        return response()->json(['message' => 'Access Denied'], 401);
    }

    // for route_list and work orders (last 10)
    function ro_wo()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {
            
            $user = auth()->user();
            $user_id = $user->id;

            $route_lists = Route_lists::where('tech_id', $user_id)->whereHas('route', function ($query) {
                                $query->whereNull('deleted');
                            })->latest()->take(10)->get();
                                
            foreach($route_lists as $list){
                $list->no = $list->route->num;
                $list->name = $list->route->name;
            }

            $work_orders = $user->work_orders()->latest()->take(10)->get();

            $ro_wo = [
                'routeLists' => $route_lists,
                'workorders' => $work_orders,
            ];

            return $ro_wo;
            
        }

        return response()->json(['message' => 'Access Denied'], 401);
    }
}
