<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Work_orders;
use App\Models\User;
//use App\Models\Stores;
use App\Models\Routes;
use App\Models\Route_lists;
use App\Models\Ro_locations;
use App\Models\Testings;
use App\Models\Cus_licenses;
use App\Models\Comp_docs;
use App\Http\Controllers\Dashboard\Comp_docsController;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user() && auth()->user()->role < 7) {

            if(request()->routeIs('dashboard')) {

                $type = request('type', '');
                $type = ( $type == 'Equipments' ? 'Equipment' : ( $type == 'Fuel Tanks' ? 'Fuel Tank' : ( $type == 'Pumps' ? 'Pump' : ( $type == 'General' ? 'General' : '' ))));
                $store = request('store', '');
                $store = $store == 'All' ? '' : $store;

                $dateRange  = request('dateRange', '');

                if($dateRange == 1 || $dateRange == ''){
                    $dateRange = date('m-d-Y', strtotime('-30 days')) . ' - ' . date('m-d-Y');
                } elseif($dateRange == 6){
                    $dateRange = date('m-d-Y', strtotime('-06 months')) . ' - ' . date('m-d-Y');
                } elseif($dateRange == 12){
                    $dateRange = date('m-d-Y', strtotime('-12 monthss')) . ' - ' . date('m-d-Y');
                }

                $user = auth()->user();
                $auth_role = auth()->user()->role;
                $auth_id = auth()->user()->id;

                $currentYear = now()->year;
                $lastYear = now()->subYear()->year;
                $monthFCounts = []; 
                $monthPCounts = [];
                for ($month = 1; $month <= 12; $month++) {
                    $Fcount = Testings::when($auth_role > 4, function ($query) use ($auth_id) { return $query->where('tech_id', $auth_id)->orWhere('cus_id', $auth_id); })->whereYear('created_at', $currentYear)->whereMonth('created_at', $month)->where('status', 'completed')->count();                    
                    $monthFCounts[$month] = $Fcount > 0 ? $Fcount : 0;

                    $Pcount = Testings::when($auth_role > 4, function ($query) use ($auth_id) { return $query->where('tech_id', $auth_id)->orWhere('cus_id', $auth_id); })->whereYear('created_at', $currentYear)->whereMonth('created_at', $month)->where('status', 'pending')->count();                    
                    $monthPCounts[$month] = $Pcount > 0 ? -1 * $Pcount : 0;
                }
                $monthlyF_ins = implode(',', $monthFCounts);
                $monthlyP_ins = implode(',', $monthPCounts);

                $work_orders = Work_orders::when($auth_role > 4, function ($query) use ($auth_id) { return $query->where('tech_id', $auth_id)->orWhere('customer_id', $auth_id); })->get();

                $total_count = count($work_orders) ?? 0;

                if($total_count != 0){
                    $currentyrtotal = count(Work_orders::when($auth_role > 4, function ($query) use ($auth_id) { return $query->where('tech_id', $auth_id)->orWhere('customer_id', $auth_id); })->whereYear('created_at', $currentYear)->get()) ?? 0;
                    $lastyrtotal = count(Work_orders::when($auth_role > 4, function ($query) use ($auth_id) { return $query->where('tech_id', $auth_id)->orWhere('customer_id', $auth_id); })->whereYear('created_at', $lastYear)->get()) ?? 0;
                    $pending_count = count($work_orders->where('status', '=', 'Pending')) ?? 0;
                    $acknowladge_count = count($work_orders->where('status', '=', 'Acknowladge')) ?? 0;
                    $finished_count = count($work_orders->where('status', '=', 'Finished')) ?? 0;
                    $rtr_count = count($work_orders->where('status', '=', 'RTR')) ?? 0;
                    $pending_perc = number_format(((($pending_count) * 100) / $total_count), 0);
                    $acknowladge_perc = number_format(((($acknowladge_count) * 100) / $total_count), 0);
                    $finished_perc = number_format(((($finished_count) * 100) / $total_count), 0);
                    $rtr_perc = number_format(((($rtr_count) * 100) / $total_count), 0);
                } else {
                    $currentyrtotal = 0;
                    $lastyrtotal = 0;
                    $pending_count = 0;
                    $pending_perc = 0;
                    $acknowladge_count = 0;
                    $finished_count = 0;
                    $rtr_count = 0;
                    $acknowladge_perc = 0;
                    $finished_perc = 0;
                    $rtr_perc = 0;
                }

                if($user->role < 5){
                    $am_locations = Ro_locations::whereHas('customer', function($query) {
                                        $query->where('com_to_inv', 'AMTS')->whereNull('deleted');
                                    })->get();
                    $unique_am_locs = $am_locations->unique('route_id');

                    $tot_am_ro = 0;
                    $tot_am_com_ro = 0;
                    foreach ($unique_am_locs as $loc) {
                        if (Carbon::parse($loc->route->created_at)->isCurrentMonth()) {
                            if(!$loc->route->route_lists()->where('status', '!=', 'completed')->first()){
                                $tot_am_com_ro ++;
                            }
                        }
                        
                        if(!$loc->route->deleted) {
                            $tot_am_ro ++;
                        }
                    }


                    $pt_locations = Ro_locations::whereHas('customer', function($query) {
                                        $query->where('com_to_inv', 'Petro-Tank Solutions')->whereNull('deleted');
                                    })->get();
                    $unique_pt_locs = $pt_locations->unique('route_id');

                    $tot_pt_ro = 0;
                    $tot_pt_com_ro = 0;
                    foreach ($unique_pt_locs as $loc) {
                        if (Carbon::parse($loc->route->created_at)->isCurrentMonth()) {
                            if(!$loc->route->route_lists()->where('status', '!=', 'completed')->first()){
                                $tot_pt_com_ro ++;
                            }
                        }
                        
                        if(!$loc->route->deleted) {
                            $tot_pt_ro ++;
                        }
                    }

                } else {
                    $tot_am_ro = '';
                    $tot_pt_ro = '';
                    $tot_am_com_ro = '';
                    $tot_pt_com_ro = '';
                }
                
                $now = Carbon::now();
                $nextMonth = Carbon::now()->addMonths(2);
                
                $stores_cnt = 0;
                if($user->role < 5){
                    $ex_licenses = Cus_licenses::where(function($query) use ($now) {
                                    $query->where('expire_date', '<', $now->toDateString());
                                })->orWhere(function($query) use ($now, $nextMonth) {
                                    $query->whereBetween('expire_date', [$now->toDateString(), $nextMonth->toDateString()]);
                                })->get();
                } else {
                    $ex_licenses = Cus_licenses::where(function($query) use ($now, $user) {
                                    $query->where('customer_id', $user->id)->where('expire_date', '<', $now->toDateString());
                                })->orWhere(function($query) use ($now, $nextMonth, $user) {
                                    $query->where('customer_id', $user->id)->whereBetween('expire_date', [$now->toDateString(), $nextMonth->toDateString()]);
                                })->get();
                                
                    $stores = $user->stores;
                    foreach($stores as $str){
                        $str_ex_licenses = Cus_licenses::where(function($query) use ($now, $str) {
                                    $query->where('customer_id', $str->id)->where('expire_date', '<', $now->toDateString());
                                })->orWhere(function($query) use ($now, $nextMonth, $str) {
                                    $query->where('customer_id', $str->id)->whereBetween('expire_date', [$now->toDateString(), $nextMonth->toDateString()]);
                                })->get();
                                
                        $ex_licenses = $ex_licenses->merge($str_ex_licenses);
                        $stores_cnt++;
                    }
                }

                $sup_ad_cnt = count(User::where('role', 1)->get()) ?? 0;
                $ad_cnt = count(User::where('role', 2)->get()) ?? 0;
                $staff_cnt = count(User::where('role', 3)->get()) ?? 0;
                $ft_sup_cnt = count(User::where('role', 4)->get()) ?? 0;
                $ft_cnt = count(User::where('role', 5)->get()) ?? 0;
                $cus_cnt = count(User::where('role', 6)->where('cus_type', 'Monthly')->get()) ?? 0;
                $cus_cnt_ann = count(User::where('role', 6)->where('cus_type', 'Annual')->get()) ?? 0;
                $cus_cnt_mnth = count(User::where('role', 6)->where('cus_type', 'Monthly')->get()) ?? 0;
                $mnth_amts_tot  = count(User::where('role', 6)->whereNull('deleted')->where('cus_type', 'Monthly')->where('com_to_inv', 'AMTS')->get()) ?? 0;
                $anual_amts_tot = count(User::where('role', 6)->whereNull('deleted')->where('cus_type', 'Annual')->where('com_to_inv', 'AMTS')->get()) ?? 0;
                $mnth_pts_tot   = count(User::where('role', 6)->whereNull('deleted')->where('cus_type', 'Monthly')->where('com_to_inv', 'Petro-Tank Solutions')->get()) ?? 0;
                $anual_pts_tot  = count(User::where('role', 6)->whereNull('deleted')->where('cus_type', 'Annual')->where('com_to_inv', 'Petro-Tank Solutions')->get()) ?? 0;              

                return view('dashboard', compact('stores_cnt', 'ex_licenses', 'mnth_amts_tot', 'anual_amts_tot', 'mnth_pts_tot', 'anual_pts_tot', 'tot_am_ro', 'tot_pt_ro', 'tot_am_com_ro', 'tot_pt_com_ro', 'pending_count', 'acknowladge_count', 'finished_count', 'rtr_count', 'pending_perc', 'acknowladge_perc', 'finished_perc', 'rtr_perc', 'monthlyF_ins', 'monthlyP_ins', 'currentyrtotal', 'lastyrtotal', 'sup_ad_cnt', 'sup_ad_cnt', 'ad_cnt', 'staff_cnt', 'ft_sup_cnt', 'ft_cnt', 'cus_cnt', 'cus_cnt_mnth', 'cus_cnt_ann'));
            }

            return view('dashboard');
        }

        Auth::logout();
        return redirect('login');
    }
}
