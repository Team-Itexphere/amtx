<style>
body {
    background: #f3f5f9;
}
</style>

    <div class="position-sticky" id="desk-head">
        {{--<div class="px-3 mt-2 mb-4 text-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" class="rounded shadow m-auto" style="max-width:70%; height:auto;">
            </a>
        </div>--}}
        <ul class="nav flex-column">
            @if(Auth::user()->role < 7)
            <li class="nav-item">
                <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-home"></i> Dashboard
                </a>
            </li>
            @endif
            @if(Auth::user()->role < 4)
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard/employees') && request()->query('edit') != Auth::user()->id ? 'active' : '' }}" href="{{ route('employees', ['list']) }}">
                    <i class="fa-solid fa-users"></i> User Management
                <b class="float-end">&raquo;</b></a>
                <ul class="submenu dropdown-menu" style="{{ Auth::user()->role == 1 ? 'min-width: 175px;' : '' }}">
                    @if(Auth::user()->role < 3)
        			<li><a class="nav-link" href="{{ route('employees', ['list', 'emp']) }}">View Employees</a></li>
        			@endif
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
            <li class="nav-item">
                <a class="nav-link {{ Route::is('my-pictures') ? 'active' : '' }}" href="{{ route('my-pictures') }}">
                    <i class="fa-solid fa-images"></i> Store Images
                </a>
            </li>
            @endif
            @if(Auth::user()->role == 1)
            <li class="nav-item">
                <a class="nav-link {{ Route::is('accounting') ? 'active' : '' }}" href="{{ route('accounting') }}">
                    <i class="fa-solid fa-landmark"></i> Accounting
                </a>
            </li>
            @endif
            @if(Auth::user()->role < 3)
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
            @if(Auth::user()->role == 5)
            <li class="nav-item">
                <a class="nav-link {{ Route::is('routes') ? 'active' : '' }}" href="{{ route('routes', ['route_lists']) }}">
                    <i class="fa-solid fa-route"></i> Route Lists
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
                <ul class="submenu dropdown-menu" style="max-height: 250px; overflow-y: auto; overflow-x: hidden;">
                    <li><a class="nav-link" href="{{ route('tests', ['list']) }}">View List</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'release-detection-annual-testing']) }}">RDA Testing</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'atg-test']) }}">ATG Test</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'cs-test']) }}">CS Test</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'line-leak-test']) }}">Line Leak Test</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'ls-test']) }}">LS Test</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'overfill-test']) }}">Overfill Test</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'sb-test']) }}">SB Test</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'gcp-test']) }}">GCP Test</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'iccp-test']) }}">ICCP Test</a></li>
                    <li><a class="nav-link" href="{{ route('tests', ['type' => 'stage-1-test']) }}">Stage 1 Test</a></li>
        		</ul>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ Route::is('testing') ? 'active' : '' }}" href="{{ route('testing', ['company' => Auth::user()->role == 6 && Auth::user()->com_to_inv == 'AMTS' ? 'AMTS' : 'Petro-Tank Solutions']) }}">
                    <i class="fa-solid fa-check"></i> Monthly Inspections
                    <b class="float-end">&raquo;</b>
                </a>
                <ul class="submenu dropdown-menu">
                    <li><a class="nav-link" href="{{ route('testing', ['company' => Auth::user()->role == 6 && Auth::user()->com_to_inv == 'AMTS' ? 'AMTS' : 'Petro-Tank Solutions']) }}">Inspection Docs</a></li>
                    @if(Auth::user()->role == 1)
            			<li><a class="nav-link" href="{{ route('testing', ['questions']) }}">Questions</a></li>
            		@endif
            	</ul>
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
            @if(Auth::user()->role !== 5)
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
            @if(Auth::user()->role !== 5)
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
        {{--<div class="m-2">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" class="rounded shadow" style="height: 100%;">
            </a>
        </div>--}}
        <div class="navbar p-0">
            <a href="/" class="navbar-brand ps-1 me-0 main-logo">
                <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" height="70" class="amt-logo" />
            </a>
            <img src="{{ asset('img') }}/pts-logo.png" class="ms-5 me-auto pts-logo" height="70" />
        </div>
        <button class="btn text-white m-2" id="mob-nav-toggle" type="button" style="background: #ed6d2b; padding: 5px 7px 2px 7px;" data-bs-toggle="offcanvas" data-bs-target="#nav-offcanvas" aria-controls="nav-offcanvas">
            <i class="fa-solid fa-bars fw-bold" style="font-size: 1.8rem;"></i>
        </button>
    </div>
    <div class="offcanvas offcanvas-start" style="max-width: 80%;" tabindex="-1" id="nav-offcanvas" aria-labelledby="nav-offcanvasLabel">
        <div class="offcanvas-header justify-content-end">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
    {{--<div class="px-3 mt-3 mb-4 text-center">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" class="rounded shadow w-75" style="height:auto;">
        </a>
    </div>--}}
    <ul class="nav flex-column">
        @if(Auth::user()->role < 7)
        <li class="nav-item">
            <a class="nav-link d-flex {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-home"></i> Dashboard
            </a>
        </li>
        @endif
        
        @if(Auth::user()->role < 4)
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between {{ request()->is('dashboard/employees') && request()->query('edit') != Auth::user()->id ? 'active' : '' }}">
                <span>
                    <i class="fa-solid fa-users"></i> User Management
                </span>
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
            <a class="nav-link d-flex {{ Route::is('employees') ? 'active' : '' }}" href="{{ route('employees', ['list', 'cus', 'parent' => Auth::user()->id]) }}">
                <i class="fa-solid fa-institution"></i> Stores
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex {{ Route::is('my-pictures') ? 'active' : '' }}" href="{{ route('my-pictures') }}">
                <i class="fa-solid fa-images"></i> Store Images
            </a>
        </li>
        @endif
        
        @if(Auth::user()->role == 1)
        <li class="nav-item">
            <a class="nav-link d-flex {{ Route::is('accounting') ? 'active' : '' }}" href="{{ route('accounting') }}">
                <i class="fa-solid fa-landmark"></i> Accounting
            </a>
        </li>
        @endif
        @if(Auth::user()->role < 3)
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between {{ Route::is('fleet') || Route::is('fleet-routing') ? 'active' : '' }}">
                <span>
                    <i class="fa-solid fa-user-group"></i> Fleets
                </span>
                <b class="float-end">&raquo;</b></a>
            <ul class="submenu dropdown-menu">
                <li><a class="nav-link" href="{{ route('fleet', ['list']) }}">View List</a></li>
                <li><a class="nav-link" href="{{ route('fleet', ['add']) }}">Add New</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between {{ Route::is('inventory') ? 'active' : '' }}">
                <span>
                    <i class="fa-solid fa-warehouse"></i> Inventory
                </span>
                <b class="float-end">&raquo;</b></a>
            <ul class="submenu dropdown-menu">
                <li><a class="nav-link" href="{{ route('inventory', ['list']) }}">View List</a></li>
                <li><a class="nav-link" href="{{ route('inventory', ['add']) }}">Add New</a></li>
            </ul>
        </li>
        @endif
        
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between {{ Route::is('invoice') ? 'active' : '' }}">
                <span>
                    <i class="fa-solid fa-newspaper"></i> Invoices
                </span>
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
            <a class="nav-link d-flex {{ Route::is('reports') ? 'active' : '' }}" href="{{ route('reports') }}">
                <i class="fa-solid fa-file"></i> Reports
            </a>
        </li>
        @endif
        
        @if(Auth::user()->role == 5)
        <li class="nav-item">
            <a class="nav-link d-flex {{ Route::is('routes') ? 'active' : '' }}" href="{{ route('routes', ['route_lists']) }}">
                <i class="fa-solid fa-route"></i> Route Lists
            </a>
        </li>
        @endif
        
        @if(Auth::user()->role < 4)
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between {{ Route::is('routes') ? 'active' : '' }}">
                <span>
                    <i class="fa-solid fa-route"></i> Routes
                </span>
                <b class="float-end">&raquo;</b></a>
            <ul class="submenu dropdown-menu">
                <li><a class="nav-link" href="{{ route('routes', ['list']) }}">Route List</a></li>
                <li><a class="nav-link" href="{{ route('routes', ['add']) }}">Add New</a></li>
                <li><a class="nav-link" href="{{ route('routes', ['route_lists']) }}">Assign Route</a></li>
            </ul>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between {{ Route::is('testing') ? 'active' : '' }}">
                <span>
                    <i class="fa-solid fa-check"></i> Monthly Inspections
                </span>
                <b class="float-end">&raquo;</b>
            </a>
            <ul class="submenu dropdown-menu">
                <li><a class="nav-link" href="{{ route('testing', ['company' => Auth::user()->role == 6 && Auth::user()->com_to_inv == 'AMTS' ? 'AMTS' : 'Petro-Tank Solutions']) }}">Inspection Docs</a></li>
                @if(Auth::user()->role == 1)
            		<li><a class="nav-link" href="{{ route('testing', ['questions']) }}">Questions</a></li>
            	@endif
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between {{ Route::is('work-orders') ? 'active' : '' }}">
                <span>
                    <i class="fa-solid fa-calendar-days"></i> Work Orders
                </span>
                <b class="float-end">&raquo;</b></a>
            <ul class="submenu dropdown-menu">
                <li><a class="nav-link" href="{{ route('work-orders', ['list']) }}">View List</a></li>
                <li><a class="nav-link" href="{{ route('work-orders', ['list','comp']) }}">Completed</a></li>
                <li><a class="nav-link" href="{{ route('work-orders', ['add']) }}">Add New</a></li>
            </ul>
        </li>

        @if(Auth::user()->role !== 5)
        <li class="nav-item">
            <a class="nav-link d-flex {{ Route::is('cus-notes') ? 'active' : '' }}" href="{{ route('cus-notes') }}">
                <i class="fa-solid fa-pen-alt"></i> Notes
            </a>
        </li>
        @endif

        @if(Auth::user()->role == 1)
        <li class="nav-item">
            <a class="nav-link d-flex {{ Route::is('settings') ? 'active' : '' }}" href="{{ route('settings', ['view']) }}">
                <i class="fa-solid fa-sliders"></i> Settings
            </a>
        </li>
        @endif

        @if(Auth::user()->role !== 5)
        <li class="nav-item">
            <a class="nav-link d-flex {{ Route::is('my-profile') ? 'active' : '' }}" href="{{ route('my-profile') }}">
                <i class="fa-solid fa-user"></i> My Profile
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-sign-out-alt"></i> {{ __('Logout ') }}
            </a>
        </li>
    </ul>
</div>

    </div>