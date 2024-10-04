<style>
body {
    background: #f3f5f9;
}
</style>

    <div class="position-sticky" id="desk-head">
        <div class="px-3 mt-2 mb-4 text-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" class="rounded shadow m-auto" style="max-width:70%; height:auto;">
            </a>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-home"></i> Dashboard
                </a>
            </li>
            @if(Auth::user()->role < 4)
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard/employees') && request()->query('edit') != Auth::user()->id ? 'active' : '' }}" href="{{ route('employees', ['list']) }}">
                    <i class="fa-solid fa-users"></i> User Management
                <b class="float-end">&raquo;</b></a>
                <ul class="submenu dropdown-menu" style="{{ Auth::user()->role == 1 ? 'min-width: 175px;' : '' }}">
        			<li><a class="nav-link" href="{{ route('employees', ['list', 'emp']) }}">View Employees</a></li>
                    <li><a class="nav-link" href="{{ route('employees', ['list', 'cus']) }}">View Customers</a></li>
                    @if(Auth::user()->role == 1)
                        <li><a class="nav-link" href="{{ route('employees', ['list', 'inactive_cus']) }}">Inactive Customers</a></li>
                    @endif
        		    <li><a class="nav-link" href="{{ route('employees', ['add']) }}">Add New</a></li>
        		</ul>
            </li>
            @endif
            @if(Auth::user()->role == 6)
            <li class="nav-item">
                <a class="nav-link {{ Route::is('employees') ? 'active' : '' }}" href="{{ route('employees', ['list', 'cus', 'parent' => Auth::user()->id]) }}">
                    <i class="fa-solid fa-institution"></i> Stores
                </a>
            </li>
            @endif
            @if(Auth::user()->role == 1)
            <li class="nav-item">
                <a class="nav-link {{ Route::is('accounting') ? 'active' : '' }}" href="{{ route('accounting') }}">
                    <i class="fa-solid fa-landmark"></i> Accounting
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('fleet') || Route::is('fleet-routing') ? 'active' : '' }}" href="{{ route('fleet') }}">
                    <i class="fa-solid fa-user-group"></i> Fleets
                <b class="float-end">&raquo;</b></a>
                <ul class="submenu dropdown-menu">
        			<li><a class="nav-link" href="{{ route('fleet', ['list']) }}">View List</a></li>
        		    <li><a class="nav-link" href="{{ route('fleet', ['add']) }}">Add New</a></li>
        		</ul>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('inventory') ? 'active' : '' }}" href="{{ route('inventory') }}">
                    <i class="fa-solid fa-warehouse"></i> Inventory
                <b class="float-end">&raquo;</b></a>
                <ul class="submenu dropdown-menu">
        			<li><a class="nav-link" href="{{ route('inventory', ['list']) }}">View List</a></li>
        		    <li><a class="nav-link" href="{{ route('inventory', ['add']) }}">Add New</a></li>
        		</ul>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ Route::is('invoice') ? 'active' : '' }}" href="{{ Auth::user()->role == 6 ? route('invoice', ['store' => Auth::user()->id]) : route('invoice') }}">
                    <i class="fa-solid fa-newspaper"></i> Invoices
                <b class="float-end">&raquo;</b></a>
                <ul class="submenu dropdown-menu">
        			<li><a class="nav-link" href="{{ route('invoice', ['list']) }}">View List</a></li>
                    @if(Auth::user()->role != 6)
        		        <li><a class="nav-link" href="{{ route('invoice', ['add']) }}">Add New</a></li>
                    @endif
        		</ul>
            </li>
            @if(Auth::user()->role == 1)
            <li class="nav-item">
                <a class="nav-link {{ Route::is('reports') ? 'active' : '' }}" href="{{ route('reports') }}">
                    <i class="fa-solid fa-file"></i> Reports
                </a>
            </li>
            @endif
            @if(Auth::user()->role < 4)
            <li class="nav-item">
                <a class="nav-link {{ Route::is('routes') ? 'active' : '' }}" href="{{ route('routes') }}">
                    <i class="fa-solid fa-route"></i> Routes
                <b class="float-end">&raquo;</b></a>
                <ul class="submenu dropdown-menu">
        			<li><a class="nav-link" href="{{ route('routes', ['list']) }}">Route List</a></li>                    
        		    <li><a class="nav-link" href="{{ route('routes', ['add']) }}">Add New</a></li>
                    <li><a class="nav-link" href="{{ route('routes', ['route_lists']) }}">Assign Route</a></li>
        		</ul>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('tests') ? 'active' : '' }}" href="{{ route('tests') }}">
                    <i class="fa-solid fa-file"></i> Testing
                <b class="float-end">&raquo;</b></a>
                <ul class="submenu dropdown-menu">
                    <li><a class="nav-link" href="{{ route('tests', ['list']) }}">View List</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'release-detection-annual-testing']) }}">RDA Testing</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'atg-test']) }}">ATG Test</a></li>
        			<!--li><a class="nav-link" href="{{ route('testing', ['line']) }}">Line & Leak</a></li>                    
        		    <li><a class="nav-link" href="{{ route('testing', ['stage']) }}">Stage 1</a></li>
                    <li><a class="nav-link" href="{{ route('testing', ['cal']) }}">Calibration</a></li-->
        		</ul>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ Route::is('testing') ? 'active' : '' }}" href="{{ route('testing') }}">
                    <i class="fa-solid fa-check"></i> Inspection Docs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('work-orders') ? 'active' : '' }}" href="{{ route('work-orders', ['list']) }}">
                    <i class="fa-solid fa-calendar-days"></i> Work Orders
                <b class="float-end">&raquo;</b></a>
                <ul class="submenu dropdown-menu">
        			<li><a class="nav-link" href="{{ route('work-orders', ['list']) }}">View List</a></li>
        			<li><a class="nav-link" href="{{ route('work-orders', ['list','comp']) }}">Completed</a></li>
        		    <li><a class="nav-link" href="{{ route('work-orders', ['add']) }}">Add New</a></li>
        		</ul>
            </li>
            @if(Auth::user()->role < 5)
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('cus-notes') ? 'active' : '' }}" href="{{ route('cus-notes') }}">
                        <i class="fa-solid fa-pen-alt"></i> Notes
                    </a>
                </li>
            @endif
            @if(Auth::user()->role == 1)
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('settings') ? 'active' : '' }}" href="{{ route('settings', ['view']) }}">
                        <i class="fa-solid fa-sliders"></i> Settings
                    </a>
                </li>
            @endif
            @if(Auth::user()->role < 5)
            <li class="nav-item">
                <a class="nav-link {{ Route::is('my-profile') ? 'active' : '' }}" href="{{ route('my-profile') }}">
                    <i class="fa-solid fa-user"></i> My Profile
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-sign-out-alt"></i> {{ __('Logout ') }}
                </a>
            </li>
        </ul>
    </div>

    <div class="justify-content-between" id="mob-head">
        <div class="m-2">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" class="rounded shadow" style="height: 100%;">
            </a>
        </div>
        <button class="btn btn-primary m-2" id="mob-nav-toggle" type="button" style="background: transparent; padding: 5px 7px 2px 7px;" data-bs-toggle="offcanvas" data-bs-target="#nav-offcanvas" aria-controls="nav-offcanvas">
            <i class="fa-solid fa-bars display-6"></i>
        </button>
    </div>
    <div class="offcanvas offcanvas-start" style="max-width: 80%;" tabindex="-1" id="nav-offcanvas" aria-labelledby="nav-offcanvasLabel">
        <div class="offcanvas-header justify-content-end">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="px-3 mt-3 mb-4 text-center">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" class="rounded shadow" style="max-width:40%; height:auto;">
                </a>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-home"></i> Dashboard
                    </a>
                </li>
                <!--li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Route::is('stores') ? 'active' : '' }}" href="{{ route('stores', ['list']) }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-store"></i> Stores
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-center" href="{{ route('stores', ['list']) }}">View List</a></li>
                        @if(Auth::user()->role < 3)
                        <li><a class="dropdown-item text-center" href="{{ route('stores', ['list', 'inactive']) }}">Inactive List</a></li>
                        @endif
                        @if(Auth::user()->role < 3)
                        <li><a class="dropdown-item text-center" href="{{ route('stores', ['add']) }}">Add New</a></li>
                        @endif
                    </ul>
                </li>
                @if(Auth::user()->role < 4)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('dashboard/employees') && request()->query('edit') != Auth::user()->id ? 'active' : '' }}" href="{{ route('employees', ['list']) }}"  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-users"></i> Employees
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-center" href="{{ route('employees', ['list']) }}">View List</a></li>
                        <li><a class="dropdown-item text-center" href="{{ route('employees', ['add']) }}">Add New</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->role == 1)
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('communication') ? 'active' : '' }}" href="{{ route('communication') }}">
                        <i class="fa-solid fa-comments"></i> Communication
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('work-orders') ? 'active' : '' }}" href="{{ route('work-orders', ['list']) }}">
                        <i class="fa-solid fa-calendar-days"></i> Work Orders
                    </a>
                </li>
                @if(Auth::user()->role == 1)
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('settings') ? 'active' : '' }}" href="{{ route('settings', ['view']) }}">
                            <i class="fa-solid fa-sliders"></i> Settings
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('my-profile') ? 'active' : '' }}" href="{{ route('my-profile') }}">
                        <i class="fa-solid fa-user"></i> My Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-sign-out-alt"></i> {{ __('Logout ') }}
                    </a>
                </li-->
            </ul>
        </div>
    </div>