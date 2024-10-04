@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show z-0" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Store <b>»</b> {{ $store->name }}</h2>
        <div>
            <button class="btn btn-primary" onclick="window.location.href='{{ route('stores', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
            @if(Auth::user()->role < 3)
            <button class="btn btn-primary" onclick="window.location.href='{{ route('stores', ['edit' => $store->id]) }}'">Edit Store</button>
            @endif
        </div>
    </div>
    <ul class="nav nav-tabs" id="myTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ !request()->has('em') && !request()->has('eq') && !request()->has('li') && !request()->has('ft') && !request()->has('pmp') && !request()->has('wo') && !request()->has('tnt') && !request()->has('ut') && !request()->has('vn') ? 'active' : '' }}" id="store-info-tab" data-bs-toggle="tab" href="#store-info" role="tab" aria-controls="store-info" aria-selected="true">Store Information</a>
        </li>
        @if(Auth::user()->role < 4)
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('em') ? 'active' : '' }}" id="employees-tab" data-bs-toggle="tab" href="#employees" role="tab" aria-controls="employees" aria-selected="false">Employees</a>
        </li>
        @endif
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('li') ? 'active' : '' }}" id="licenses-tab" data-bs-toggle="tab" href="#licenses" role="tab" aria-controls="licenses" aria-selected="false">License & Permit</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('eq') ? 'active' : '' }}" id="equipments-tab" data-bs-toggle="tab" href="#equipments" role="tab" aria-controls="equipments" aria-selected="false">Equipments</a>
        </li>        
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('ft') ? 'active' : '' }}" id="fuel-tanks-tab" data-bs-toggle="tab" href="#fuel-tanks" role="tab" aria-controls="fuel-tanks" aria-selected="false">Fuel Tanks</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('pmp') ? 'active' : '' }}" id="pumps-tab" data-bs-toggle="tab" href="#pumps" role="tab" aria-controls="pumps" aria-selected="false">Pumps</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('wo') ? 'active' : '' }}" id="work_orders-tab" data-bs-toggle="tab" href="#work_orders" role="tab" aria-controls="work_orders" aria-selected="false">Work Orders</a>
        </li>
        @if(Auth::user()->role < 4)
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('tnt') ? 'active' : '' }}" id="tenants-tab" data-bs-toggle="tab" href="#tenants" role="tab" aria-controls="tenants" aria-selected="false">Tenants</a>
        </li>
        @endif
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('ut') ? 'active' : '' }}" id="utilities-tab" data-bs-toggle="tab" href="#utilities" role="tab" aria-controls="utilities" aria-selected="false">Utilities</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request()->has('vn') ? 'active' : '' }}" id="vendors-tab" data-bs-toggle="tab" href="#vendors" role="tab" aria-controls="vendors" aria-selected="false">Vendors</a>
        </li>
    </ul>

    <div class="tab-content p-4 pt-5">
        <div class="tab-pane fade mt-4 {{ !request()->has('em') && !request()->has('eq') && !request()->has('li') && !request()->has('ft') && !request()->has('pmp') && !request()->has('wo') && !request()->has('tnt') && !request()->has('ut') && !request()->has('vn') ? 'show active' : '' }}" id="store-info" role="tabpanel" aria-labelledby="store-info-tab">
            @include('layouts.dashboard.stores.tabs.info')
        </div>
        <div class="tab-pane fade mt-4 {{ request()->has('eq') ? 'show active' : '' }}" id="equipments" role="tabpanel" aria-labelledby="equipments-tab">
            @include('layouts.dashboard.stores.tabs.equipments')
        </div>
        @if(Auth::user()->role < 4)
        <div class="tab-pane fade mt-4 {{ request()->has('em') ? 'show active' : '' }}" id="employees" role="tabpanel" aria-labelledby="employees-tab">
           @include('layouts.dashboard.stores.tabs.employees')
        </div>
        @endif
        <div class="tab-pane fade mt-4 {{ request()->has('li') ? 'show active' : '' }}" id="licenses" role="tabpanel" aria-labelledby="licenses-tab">
            @include('layouts.dashboard.stores.tabs.store_licenses')
        </div>
        <div class="tab-pane fade mt-4 {{ request()->has('ft') ? 'show active' : '' }}" id="fuel-tanks" role="tabpanel" aria-labelledby="fuel-tanks-tab">
            @include('layouts.dashboard.stores.tabs.fuel-tanks')
        </div>
        <div class="tab-pane fade mt-4 {{ request()->has('pmp') ? 'show active' : '' }}" id="pumps" role="tabpanel" aria-labelledby="pumps-tab">
            @include('layouts.dashboard.stores.tabs.pumps')
        </div>
        <div class="tab-pane fade mt-4 {{ request()->has('wo') ? 'show active' : '' }}" id="work_orders" role="tabpanel" aria-labelledby="work_orders-tab">
            @include('layouts.dashboard.stores.tabs.work_orders')
        </div>
        @if(Auth::user()->role < 4)
        <div class="tab-pane fade mt-4 {{ request()->has('tnt') ? 'show active' : '' }}" id="tenants" role="tabpanel" aria-labelledby="tenants-tab">
            @include('layouts.dashboard.stores.tabs.tenants')
        </div>
        @endif
        <div class="tab-pane fade mt-4 {{ request()->has('ut') ? 'show active' : '' }}" id="utilities" role="tabpanel" aria-labelledby="utilities-tab">
            @include('layouts.dashboard.stores.tabs.utilities')
        </div>
        <div class="tab-pane fade mt-4 {{ request()->has('vn') ? 'show active' : '' }}" id="vendors" role="tabpanel" aria-labelledby="vendors-tab">
            @include('layouts.dashboard.stores.tabs.vendors')
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var base_url = window.location.href;
    var store_tab_par = 'view={{ $store->id }}';
    var eq_tab_par = store_tab_par + '&eq';
    var em_tab_par = store_tab_par + '&em';
    var li_tab_par = store_tab_par + '&li';
    var ft_tab_par = store_tab_par + '&ft';
    var pmp_tab_par = store_tab_par + '&pmp';
    var wo_tab_par = store_tab_par + '&wo';
    var tnt_tab_par = store_tab_par + '&tnt';
    var ut_tab_par = store_tab_par + '&ut';
    var vn_tab_par = store_tab_par + '&vn';

    $('#equipments-tab').on('click', function() {
        updateUrl(eq_tab_par);
    });

    $('#store-info-tab').on('click', function() {
        updateUrl(store_tab_par);
    });

    $('#employees-tab').on('click', function() {
        updateUrl(em_tab_par);
    });

    $('#licenses-tab').on('click', function() {
        updateUrl(li_tab_par);
    });
    
    $('#fuel-tanks-tab').on('click', function() {
        updateUrl(ft_tab_par);
    });
    
    $('#pumps-tab').on('click', function() {
        updateUrl(pmp_tab_par);
    });

    $('#work_orders-tab').on('click', function() {
        updateUrl(wo_tab_par);
    });

    $('#tenants-tab').on('click', function() {
        updateUrl(tnt_tab_par);
    });
    
    $('#utilities-tab').on('click', function() {
        updateUrl(ut_tab_par);
    });

    $('#vendors-tab').on('click', function() {
        updateUrl(vn_tab_par);
    });

    function updateUrl(newParam) {
        var newUrl = base_url.split('?')[0];
        if (newParam !== '') {
            newUrl += '?' + newParam;
        }
        window.history.pushState({path: newUrl}, '', newUrl);
        window.location.href = newUrl;
    }
    
    $('[data-bs-toggle="tab"]').each(function() {
        $(this).removeAttr('data-bs-toggle');
    });
});

</script>