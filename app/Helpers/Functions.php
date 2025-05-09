<?php

use Illuminate\Pagination\LengthAwarePaginator;

function paginateCollection($items)
{
    $perPage = request('per_page', 10);
    $page = request('page', 1);
    
    $PagesSet = array();
    $itemCount = 0;

    if($items && count($items) != 0) {
        $PagesSet = $items->forPage($page, $perPage);
        $itemCount = $items->count();
    } else {
        $items = array();
    }

    if ($perPage == -1) {
        return new LengthAwarePaginator(
            $items,
            $itemCount,
            -1,
            1,
            ['path' => url()->current()]
        );
    }

    return new LengthAwarePaginator(
        $PagesSet,
        $itemCount,
        $perPage,
        $page,
        ['path' => url()->current()]
    );
}

function filterRecords($model = null, $items = null, $type = null, $store = null, $s = null, $fltRole = null, $dateRange = null, $tab = null)
{
    $auth_role = auth()->user()->role;

    if($model){
        if (request()->routeIs('employees')) {

            $filteredRecords = auth()->user()->role > 2 && auth()->user()->role !== 6 ? $model::where('role', '=', 6)->where('deleted', null) : $model::where('role', '>=', $auth_role)->where('deleted', null);

            if($auth_role == 6){
                $filteredRecords = $model::where('role', '=', 6)->where('deleted', null);
            } else {
                
                if (request()->has('emp')) {
                    if(auth()->user()->role > 2) {
                        abort(404);
                    }
                    $filteredRecords = $model::where('role', '!=', 6)->where('deleted', null);
                } elseif (request()->has('cus')) {
                    
                    if(request()->has('parent')){
                        $parent = $model::find(request()->input('parent'));
                        $filteredRecords = $parent->stores()->where('deleted', null);
                    } else {
                        $filteredRecords = $model::where('role', 6)->where('deleted', null); // ->doesntHave('customers')
                    }
                    
                } elseif ($auth_role == 1 && request()->has('inactive_cus')) {
                    $filteredRecords = $model::where('role', 6)->whereNotNull('deleted');
                }
                
            }

        } elseif (request()->routeIs('work-orders') || request()->routeIs('wo-pdf')) {
            if($auth_role < 3){
                $filteredRecords = $model::query();
            } else {
                $filteredRecords = $model::whereIn('store_id', auth()->user()->stores()->pluck('id'));
            }
        } elseif (request()->routeIs('stores')){
            if(request()->has('view')) {

                $store_id = request('view');

                if ($tab && $tab == 'em') {

                    $filteredRecords = $model::whereHas('stores', function($query) use ($store_id) {
                        $query->where('store_id', $store_id);
                    });

                } else {
                    $filteredRecords = $model::where('store_id', $store_id);
                }                    
            } else {
                if($auth_role < 3){           
                    
                    if (request()->has('list') && request()->has('idle')) {
                        $filteredRecords = $model::where('status', '=', 'Idle');
                    } elseif (request()->has('list') && request()->has('cons')) {
                        $filteredRecords = $model::where('status', '=', 'Under Construction');
                    } elseif (request()->has('list') && request()->has('inactive')) {
                        $filteredRecords = $model::where('status', '!=', 'Complete');
                    } else {
                        $filteredRecords = $model::where('status', 'Complete')->orWhereNull('status');
                    }

                } else {
                    $filteredRecords = $model::whereIn('id', auth()->user()->stores()->pluck('id'));
                } 
            }
                
        } elseif (request()->routeIs('dashboard')){
            if($auth_role < 3){
                $filteredRecords = $model::query();
            } else {
                $filteredRecords = $model::whereIn('store_id', auth()->user()->stores()->pluck('id'));
            }                
        }
            
    } elseif(count($items) != 0) {
        $filteredRecords = $items->first()->newQuery(); 
    } else {
        return null;
    }
        
    if ($fltRole) {
        $filteredRecords = $filteredRecords->where('role', $fltRole);
    }
    if ($dateRange) {
        $dateRange = explode(' - ', $dateRange); 
        $fromDate = DateTime::createFromFormat('m-d-Y', $dateRange[0]);
        $endDate = DateTime::createFromFormat('m-d-Y', $dateRange[1]);
        $filteredRecords = $filteredRecords->where('created_at', '>=', $fromDate)->where('created_at', '<=', $endDate);
    }
    if ($type) {
        $filteredRecords = $filteredRecords->where('type', $type);
    }
    if ($store) {
        if (request()->routeIs('employees')) {
            $filteredRecords = $filteredRecords->whereHas('stores', function($query) use ($store) {
                $query->where('store_id', $store);
            });
        } else {
            $filteredRecords = $filteredRecords->where('store_id', $store);
        }            
    }
    if ($s) {
        $filteredRecords = $filteredRecords->where(function($query) use ($s) {
            $columns = \Schema::getColumnListing($query->getModel()->getTable());

            foreach($columns as $column) {
                $query->orWhere($column, 'like', '%' . $s . '%');
            }
        });
    }
        

    return $filteredRecords->get();
}