@extends('layouts.app')

@section('sidebar')

<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-white sidebar pt-lg-5 mt-lg-5 sb">
    @include('layouts.dashboard.sidebar')
</nav>

@endsection


@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4 main-right">
    @if(Auth::user()->role < 7)
        @if(Route::is('user-logs'))
            @include('layouts.dashboard.user-logs.list')
        @endif

        @if(Route::is('employees') && request()->has('list'))
            @include('layouts.dashboard.employees.list')
        @elseif(Route::is('employees') && request()->has('edit'))
            @include('layouts.dashboard.employees.edit')
        @elseif(Route::is('employees') && request()->has('add'))
            @include('layouts.dashboard.employees.add')
        @elseif(Route::is('employees'))
            @include('layouts.dashboard.employees.list')
        @endif

        @if(Route::is('cus-licenses'))
            @include('layouts.dashboard.employees.cus-licenses.list')
        @endif
        
        @if(Route::is('cus-sir-inv-docs'))
            @include('layouts.dashboard.employees.cus-sir-inv-docs.list')
        @endif
        
        @if(Route::is('cus-notes'))
            @include('layouts.dashboard.employees.cus-notes')
        @endif
        
        @if(Route::is('site-info'))
            @include('layouts.dashboard.employees.site-info.list')
        @endif
        
        @if(Route::is('comp-docs'))
            @include('layouts.dashboard.employees.comp-docs.list')
        @endif
        
        @if(Route::is('maintain-logs'))
            @include('layouts.dashboard.employees.maintain-logs.list')
        @endif
    
        @if(Route::is('fleet') && request()->has('list'))
            @include('layouts.dashboard.fleets.list')
        @elseif(Route::is('fleet') && request()->has('edit'))
            @include('layouts.dashboard.fleets.edit')
        @elseif(Route::is('fleet') && request()->has('add'))
            @include('layouts.dashboard.fleets.add')
        @elseif(Route::is('fleet'))
            @include('layouts.dashboard.fleets.list')
        @endif

        @if(Route::is('routes') && request()->has('list'))
            @include('layouts.dashboard.routes.list')
        @elseif(Route::is('routes') && request()->has('route_lists'))
            @include('layouts.dashboard.routes.route_lists')
        @elseif(Route::is('routes') && request()->has('view_rl'))
            @include('layouts.dashboard.routes.view_rl')    
        @elseif(Route::is('routes') && request()->has('edit'))
            @include('layouts.dashboard.routes.edit')    
        @elseif(Route::is('routes') && request()->has('add'))
            @include('layouts.dashboard.routes.add')
        @elseif(Route::is('routes') && request()->has('view'))
            @include('layouts.dashboard.routes.view') 
        @elseif(Route::is('routes'))
            @include('layouts.dashboard.routes.list')
        @endif

        @if(Route::is('fleet-routing') && request()->has('view'))
            @include('layouts.dashboard.fleets.fleet-routing')
        @endif

        @if(Route::is('inventory') && request()->has('list'))
            @include('layouts.dashboard.inventory.list')
        @elseif(Route::is('inventory') && request()->has('edit'))
            @include('layouts.dashboard.inventory.edit')
        @elseif(Route::is('inventory') && request()->has('add'))
            @include('layouts.dashboard.inventory.add')
        @elseif(Route::is('inventory'))
            @include('layouts.dashboard.inventory.list')
        @endif

        @if(Route::is('invoice') && request()->has('list'))
            @include('layouts.dashboard.invoice.list')
        @elseif(Route::is('invoice') && request()->has('edit'))
            @include('layouts.dashboard.invoice.edit')
        @elseif(Route::is('invoice') && request()->has('add'))
            @include('layouts.dashboard.invoice.add')
        @elseif(Route::is('invoice'))
            @include('layouts.dashboard.invoice.list')
        @endif

        @if(Route::is('work-orders') && request()->has('list'))
            @include('layouts.dashboard.work-orders.list')
        @elseif(Route::is('work-orders') && request()->has('edit'))
            @include('layouts.dashboard.work-orders.edit')
        @elseif(Route::is('work-orders') && request()->has('add'))
            @include('layouts.dashboard.work-orders.add')
        @elseif(Route::is('work-orders'))
            @include('layouts.dashboard.work-orders.list')
        @endif
    @endif
	

    @if(Route::is('settings') && Auth::user()->role == 1)
        @include('layouts.dashboard.settings')
    @endif

    @if(Route::is('tests'))
      @if (request()->has('list'))
        @include('layouts.dashboard.tests.list')
      @elseif (request()->input('type') == 'release-detection-annual-testing')
        @include('layouts.dashboard.tests.forms.release-detection-annual-testing')
      @elseif (request()->input('type') == 'atg-test')
        @include('layouts.dashboard.tests.forms.atg-test')
      @elseif (request()->input('type') == 'cs-test')
        @include('layouts.dashboard.tests.forms.containment-sump-test')
      @elseif (request()->input('type') == 'line-leak-test')
        @include('layouts.dashboard.tests.forms.line-leak-test') 
      @elseif (request()->input('type') == 'ls-test')
        @include('layouts.dashboard.tests.forms.liquid-sensor-test')
      @elseif (request()->input('type') == 'overfill-test')
        @include('layouts.dashboard.tests.forms.overfill-test')  
      @elseif (request()->input('type') == 'sb-test')
        @include('layouts.dashboard.tests.forms.spill-bucket-test')
      @elseif (request()->input('type') == 'gcp-test')
        @include('layouts.dashboard.tests.forms.galvanic-cp-test')
      @elseif (request()->input('type') == 'iccp-test')
        @include('layouts.dashboard.tests.forms.impressed-current-cp-test')
      @elseif (request()->input('type') == 'stage-1-test')
        @include('layouts.dashboard.tests.forms.stage-1-test')
      @else
        @include('layouts.dashboard.tests.list')
      @endif
    @endif

    @if(Route::is('testing'))
      @if (request()->has('questions'))
        @include('layouts.dashboard.testing.questions')
      @elseif (request()->has('edit'))
        @include('layouts.dashboard.testing.edit')
      @else
        @include('layouts.dashboard.testing.list')
      @endif
    @endif

    @if(Route::is('my-profile'))
        @include('layouts.dashboard.employees.my-profile')
    @endif
    
    @if(Route::is('my-pictures'))
        @include('layouts.dashboard.employees.my-pictures')
    @endif
    
    @if(Route::is('dashboard'))
            <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
            <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
            <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
            <link rel="stylesheet" href="../assets/css/demo.css" />
            <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
            <script src="../assets/vendor/js/helpers.js"></script>
            <script src="../assets/js/config.js"></script>
            
            <style>
                .usr-label {
                    display: block;
                    float: left;
                    width: 200px;
                    color: #566a7f;
                }
                
                .cnt {
                    color: #ec6d2b;
                }
                
                .usr-cnt {
                    display: flex;
                    height: 100%;
                    justify-content: center;
                }
                
                .bd-bot {
                    border-bottom: 1px solid #f1f1f1;
                }
                
                .ac-hd {
                    border-bottom: 1px solid #ec6d2b;
                    padding: 0 3px 2px 3px;
                }
                
                .ac-hd a {
                    text-decoration: none;
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                
                .ac-hd a[aria-expanded='true'] b {
                    rotate: 180deg;
                }
                
                .custom-card {
                    min-height: 100px;
                    display: flex;
                    align-items: center;
                    border: none;
                    border-radius: 8px;
                    color: white;
                    padding: 12px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    transition: all 0.3s ease;
                    cursor: pointer;
                }
                .custom-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
                }
                .custom-icon {
                    font-size: 1.8rem;
                    margin-right: 20px;
                    margin-left: 15px;
                    opacity: 0.9;
                }
                .custom-title {
                    font-size: 1rem;
                    font-weight: bold;
                    text-transform: uppercase;
                    margin-bottom: 2px;
                }
                .custom-value {
                    font-size: 1.5rem;
                    font-weight: bold;
                }
                .custom-card-inspection { background: linear-gradient(135deg, #007bff, #00c6ff); }
                .custom-card-next { background: linear-gradient(135deg, #28a745, #85d335); }
                .custom-card-orders { background: linear-gradient(135deg, #ffc107, #ff6f00); }
                .custom-card-invoices { background: linear-gradient(135deg, #dc3545, #ff1744); }
            </style>

            <div class="container-xxl flex-grow-1">
              <div class="row mt-4 mt-lg-0">
                <div class="col-lg-{{ auth()->user()->role < 3 ? 6 : 12 }} mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7 my-auto">
                        <div class="card-body">
                          <h1 class="card-title text-primary mb-0">Overview</h1>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="../assets/img/illustrations/man-with-laptop-light.png"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                @if(auth()->user()->role < 3)
                <div class="col-lg-6 col-md-4 order-1">
                  <div class="row">
                    
                    <div class="col-lg-6 col-md-12 col-12 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/chart-success.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <h5 class="text-end my-auto"># of Routes</h5>
                          </div>
                          <div class="d-flex mb-2 justify-content-between align-middle">
                            <p class="fw-semibold d-block my-auto">Total AMTS Routes</p>
                            <h3 class="card-title my-auto"><a href="{{ route('routes', ['list', 'cus', 'company' => 'AMTS']) }}">{{ $tot_am_ro }}</a></h3>
                          </div>
                          <div  class="d-flex justify-content-between align-middle">
                            <p class="fw-semibold d-block my-auto">Total PTS Routes</p>
                            <h3 class="card-title my-auto"><a href="{{ route('routes', ['list', 'cus', 'company' => 'Petro-Tank Solutions']) }}">{{ $tot_pt_ro }}</a></h3>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/wallet-info.png"
                                alt="Credit Card"
                                class="rounded"
                              />
                            </div>
                            <h5 class="text-end my-auto">Routes Completed</h5>
                          </div>
                          <div  class="d-flex mb-2 justify-content-between align-middle">
                            <p class="fw-semibold my-auto">AMTS Routes Completed</p>
                            <h3 class="card-title text-nowrap my-auto">{{ $tot_am_com_ro }}</h3>
                          </div>
                          <div  class="d-flex justify-content-between align-middle">
                            <p class="fw-semibold my-auto">PTS Routes Completed</p>
                            <h3 class="card-title text-nowrap my-auto">{{ $tot_pt_com_ro }}</h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endif

                
                <!-- Total Revenue -->
                <div class="col-lg-{{ auth()->user()->role < 3 ? 8 : 12 }} order-2 order-md-3 order-lg-2 mb-4">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      <div class="col-md-{{ auth()->user()->role < 3 || auth()->user()->role == 4 || auth()->user()->role == 5 ? '12 pb-3' : (auth()->user()->role == 6 ? '6 p-3' : '8 pb-3') }}" style="overflow-x: {{ auth()->user()->role !== 6 ? 'auto' : 'hidden' }}; overflow-y: hidden;">
                        @if(auth()->user()->role !== 6)
                            <div style="{{ auth()->user()->role !== 4 && auth()->user()->role !== 5 ? 'width: 750px;' : 'margin: 10px 20px 10px 10px;' }}">
                              <h5 class="card-header m-0 me-2 pb-3">Monthly Inspection</h5>
                              <div id="totalRevenueChart" class="px-2"></div>
                            </div>
                        @else
                            @php
                                $last_insp = auth()->user()->testings()->where('status', 'completed')->latest()->first();
                                $next_insp = auth()->user()->ro_locations()->latest()->first()?->route->route_lists()->whereDate('start_date', '>=', today())->first();
                                $pend_wo = auth()->user()->cus_work_orders()->where(function($query) {
                                        $query->where('status', '!=', 'Completed')->orWhereNull('status');
                                    })->count();
                                $unpaid_inv = auth()->user()->invoices()->where(function($query) {
                                        $query->where('payment', '!=', 'Paid')->orWhereNull('payment');
                                    })->count();
                            @endphp
                            {{--<div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <a href="{{ route('testing') }}" class="text-decoration-none">
                                        <div class="custom-card custom-card-inspection" style="cursor: {{ $last_insp ? 'pointer' : 'not-allowed' }};">
                                            <i class="fas fa-calendar-check custom-icon"></i>
                                            <div>
                                                <div class="custom-title">Last Inspection</div>
                                                <div class="custom-value">{{ $last_insp ? $last_insp->updated_at->format('m/d/Y') : 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('testing') }}" class="text-decoration-none">
                                        <div class="custom-card custom-card-next">
                                            <i class="fas fa-calendar-alt custom-icon"></i>
                                            <div>
                                                <div class="custom-title">Next Inspection</div>
                                                <div class="custom-value">{{ $next_insp ? \Carbon\Carbon::parse($next_insp->start_date)->format('m/d/Y') : 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>--}}
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <a href="{{ route('testing') }}" class="text-decoration-none">
                                        <div class="custom-card custom-card-inspection" style="cursor: {{ $last_insp ? 'pointer' : 'not-allowed' }};">
                                            <i class="fas fa-calendar-check custom-icon"></i>
                                            <div>
                                                <div class="custom-title">Last Inspection</div>
                                                <div class="custom-value">{{ $last_insp ? $last_insp->updated_at->format('m/d/Y') : 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('work-orders') }}" class="text-decoration-none">
                                        <div class="custom-card custom-card-orders">
                                            <i class="fas fa-tools custom-icon"></i>
                                            <div>
                                                <div class="custom-title">Pending Work Orders</div>
                                                <div class="custom-value">{{ $pend_wo }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('invoice', ['status' => 'Unpaid']) }}" class="text-decoration-none">
                                        <div class="custom-card custom-card-invoices">
                                            <i class="fas fa-file-invoice-dollar custom-icon"></i>
                                            <div>
                                                <div class="custom-title">Unpaid Invoices</div>
                                                <div class="custom-value">{{ $unpaid_inv }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                      </div>
                      @if(auth()->user()->role == 6)
                      <div class="col-md-6 p-5 d-flex justify-content-center">
                        <div class="my-auto">
                            <center><h4>Licenses & Others</h4></center>
                            @php
                                $licenses = auth()->user()->cus_licenses;
                                $not_ex_licenses = $licenses->diff($ex_licenses)->unique();
                            @endphp
                            @if($ex_licenses || $not_ex_licenses)
                                <style>
                                    #ex-li-table tr {
                                        font-size: 15px;
                                    }
                                    
                                    @media(max-width: 768px) {
                                        #ex-li-table {
                                            width: 100% !important;
                                        }
                                    }
                                </style>
                                <table id="ex-li-table" style="border: none; width: 100%; font-size: 20px;">
                                    <thead>
                                        <tr>
                                            @if($stores_cnt > 1)
                                                <th></th>
                                            @endif
                                            <th style="width: 150px"></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $risk_period = $today->copy()->addDays(29);
                                        @endphp
                                        @foreach($ex_licenses as $li)
                                            @php
                                                $ex_date = null;
                                                $ex_soon = false;
                                                if($li->expire_date){
                                                    $ex_date = \Carbon\Carbon::parse($li->expire_date);
                                                    
                                                    if($ex_date->isPast() /*|| $ex_date->between($today, $risk_period)*/) {
                                                        $ex_soon = true;
                                                    }
                                                    
                                                    $ex_date = $ex_date->format('m/d/Y');
                                                }
                                            @endphp
                                        
                                            @if($stores_cnt > 1)
                                                <tr>
                                                    <td class="bd-bot px-1"><b>{{ $li->customer->name }}</b></td>
                                                    <td class="bd-bot px-1"></td>
                                                    <td class="bd-bot px-1"></td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot px-1"></td>
                                                    <td class="bd-bot px-1">{{ $li->type == 'Annual' || $li->type == 'Monthly' || $li->type == 'Every 3 years' ? $li->name : $li->type }}</td>
                                                    <td class="bd-bot px-1 text-end" style="{{ $ex_soon ? 'font-weight: 700; color: red;' : 'color: red;' }}">{{ $ex_date }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="bd-bot px-1">{{ $li->type == 'Annual' || $li->type == 'Monthly' || $li->type == 'Every 3 years' ? \App\Http\Controllers\Dashboard\Comp_docsController::comp_doc_types($li->name) : $li->type }}</td>
                                                    <td class="bd-bot px-1 text-end" style="{{ $ex_soon ? 'font-weight: 700; color: red;' : 'color: red;' }}">{{ $ex_date }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        
                                        @foreach($not_ex_licenses as $li)
                                            @if($stores_cnt > 1)
                                                <tr>
                                                    <td class="bd-bot px-1"><b>{{ $li->customer->name }}</b></td>
                                                    <td class="bd-bot px-1"></td>
                                                    <td class="bd-bot px-1"></td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot px-1"></td>
                                                    <td class="bd-bot px-1">{{ $li->type == 'Annual' || $li->type == 'Monthly' || $li->type == 'Every 3 years' ? $li->name : $li->type }}</td>
                                                    <td class="bd-bot px-1 text-end">{{ $li->expire_date ? \Carbon\Carbon::parse($li->expire_date)->format('m/d/Y') : '' }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="bd-bot px-1">{{ $li->type == 'Annual' || $li->type == 'Monthly' || $li->type == 'Every 3 years' ? $li->name : $li->type }}</td>
                                                    <td class="bd-bot px-1 text-end">{{ $li->expire_date ? \Carbon\Carbon::parse($li->expire_date)->format('m/d/Y') : '' }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <script>
                                    $(document).ready(function() {
                                        @if($stores_cnt > 1)
                                        
                                        let seen = {};
                                        
                                        $('#ex-li-table tbody tr').each(function() {
                                            let firstColumnText = $(this).find('td:first').text().trim();
                                            
                                            if (seen[firstColumnText] && firstColumnText != '') {
                                                $(this).remove();
                                            } else {
                                                seen[firstColumnText] = true;
                                            }
                                        });
                                        
                                        @endif
                                    });
                                </script>
                            @else
                                <br><br><center>No expiring licenses</center><br><br>
                            @endif
                        </div>
                      </div>
                      @endif
                      <!--div class="col-md-4 d-flex justify-content-center">
                        <div class="my-auto">
                          <div id="growthChart"></div>
                          <div class="text-center fw-semibold pt-3 mb-2">% Total Pending</div>

                          <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                            <div class="d-flex">
                              <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-analyse text-primary"></i></span>
                              </div>
                              <div class="d-flex flex-column">
                                <small>{{ now()->subYear()->translatedFormat('Y') }} All</small>
                                <h6 class="mb-0">{{ $lastyrtotal }}</h6>
                              </div>
                            </div>
                            <div class="d-flex">
                              <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-analyse text-info"></i></span>
                              </div>
                              <div class="d-flex flex-column">
                                <small>{{ now()->translatedFormat('Y') }} All</small>
                                <h6 class="mb-0">{{ $currentyrtotal }}</h6>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div-->
                    </div>
                  </div>
                </div>
                @if(auth()->user()->role < 3)
                <!--/ Total Revenue -->
                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                  <div class="row">
                    <div class="col-12 mb-4">
                      <div class="card">
                        <div class="card-body" style="padding-top:32px; padding-bottom:33px;">
                          <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                              <div class="card-title">
                                <h5 class="text-nowrap mb-2">Yearly Summery</h5>
                                <span class="badge bg-label-warning rounded-pill">Year {{ now()->year }}</span>
                              </div>
                              <div class="mt-sm-auto">
                                <small class="text-success text-nowrap fw-semibold"><i class="bx bx-chevron-up"></i></small>
                                <h3 class="mb-0">Total: {{ $currentyrtotal }}</h3>
                              </div>
                            </div>
                            <div id="profileReportChart"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12" style="padding: 2px 13px;">
                        <div class="card card-body">
                            <h5 class="fw-semibold d-block my-2 text-center">Monthly Customers</h5>
                            <div class="d-flex flex-row justify-content-between my-2">
                                <h6 class="fw-semibold w-70 my-auto">AMTS Total</h6>
                                <h4 class="text-secondary my-auto"><a href="{{ route('employees', ['list', 'cus', 's' => 'AMTS']) }}">{{ $mnth_amts_tot }}</a></h4>
                            </div>
                            <div class="d-flex flex-row justify-content-between my-2">
                                <h6 class="fw-semibold w-70 my-auto">PTS Total</h6>
                                <h4 class="text-secondary my-auto"><a href="{{ route('employees', ['list', 'cus', 's' => 'Petro-Tank Solutions']) }}">{{ $mnth_pts_tot }}</a></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12" style="padding: 2px 13px;">
                        <div class="card card-body">
                            <h5 class="fw-semibold d-block my-2 text-center">Annual Customers</h5>
                            <div class="d-flex flex-row justify-content-between my-2">
                                <h6 class="fw-semibold w-70 my-auto">AMTS Total</h6>
                                <h4 class="text-secondary my-auto"><a href="{{ route('employees', ['list', 'cus', 's' => 'AMTS']) }}">{{ $anual_amts_tot }}</a></h4>
                            </div>
                            <div class="d-flex flex-row justify-content-between my-2">
                                <h6 class="fw-semibold w-70 my-auto">PTS Total</h6>
                                <h4 class="text-secondary my-auto"><a href="{{ route('employees', ['list', 'cus', 's' => 'Petro-Tank Solutions']) }}">{{ $anual_pts_tot }}</a></h4>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              @endif

              @if(auth()->user()->role < 3)
              <div class="row" style="overflow: auto; flex-wrap: nowrap; padding-bottom: 10px;">
                <!--div class="col-lg-2">
                  <div class="card card-body text-center usr-cnt">
                    <h5 class="fw-semibold d-block mb-1">Super Admins</h5>
                    <h2 class="text-secondary my-2">{{ $sup_ad_cnt }}</h2>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="card card-body text-center usr-cnt">
                    <h5 class="fw-semibold d-block mb-1">Admins</h5>
                    <h2 class="text-secondary my-2">{{ $ad_cnt }}</h2>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="card card-body text-center usr-cnt">
                    <h5 class="fw-semibold d-block mb-1">Office Staff</h5>
                    <h2 class="text-secondary my-2">{{ $staff_cnt }}</h2>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="card card-body text-center usr-cnt">
                    <h5 class="fw-semibold d-block mb-1">Field Tech Supervisors</h5>
                    <h2 class="text-secondary my-2">{{ $ft_sup_cnt }}</h2>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="card card-body text-center usr-cnt">
                    <h5 class="fw-semibold d-block mb-1">Field Technicians</h5>
                    <h2 class="text-secondary my-2">{{ $ft_cnt }}</h2>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="card card-body text-center usr-cnt">
                    <h5 class="fw-semibold d-block mb-1">Monthly Customers</h5>
                    <h2 class="text-secondary my-2">{{ $cus_cnt }}</h2>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="card card-body text-center usr-cnt">
                    <h5 class="fw-semibold d-block mb-1">Annual Customers</h5>
                    <h2 class="text-secondary my-2">{{ $cus_cnt_ann }}</h2>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="card card-body text-center usr-cnt">
                    <h5 class="fw-semibold d-block mb-1">Annual Customers</h5>
                    <h2 class="text-secondary my-2">{{ $cus_cnt_ann }}</h2>
                  </div>
                </div-->
                
                <!--div class="col-lg-8">
                  <div class="card card-body" style="background-image: url(/img/dash-bg-des1.jpg); background-repeat: no-repeat; background-size: 250px; background-position: right 90px;">
                    <h5 class="fw-semibold d-block mb-1 cnt"><span class="usr-label">Super Admins</span>        {{ $sup_ad_cnt }}</h5>
                    <h5 class="fw-semibold d-block mb-1 cnt"><span class="usr-label">Admins</span>              {{ $ad_cnt }}</h5>
                    <h5 class="fw-semibold d-block mb-1 cnt"><span class="usr-label">Office Staff</span>        {{ $staff_cnt }}</h5>
                    <h5 class="fw-semibold d-block mb-1 cnt"><span class="usr-label">Field Supervisors</span>   {{ $ft_sup_cnt }}</h5>
                    <h5 class="fw-semibold d-block mb-1 cnt"><span class="usr-label">Field Technicians</span>   {{ $ft_cnt }}</h5>
                  </div>
                </div>
                <div class="col-lg-2">
                    <div class="card card-body text-center">
                        <h5 class="fw-semibold d-block my-1">AMTS Total</h5>
                        <h3 class="text-secondary my-1">{{ $mnth_amts_tot }}</h3>
                        <h5 class="fw-semibold d-block my-1">PTS Total</h5>
                        <h3 class="text-secondary my-auto">{{ $mnth_pts_tot }}</h3>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="card card-body text-center">
                        <h5 class="fw-semibold d-block my-1">AMTS Total</h5>
                        <h3 class="text-secondary my-1">{{ $anual_amts_tot }}</h3>
                        <h5 class="fw-semibold d-block my-1">PTS Total</h5>
                        <h3 class="text-secondary my-auto">{{ $anual_pts_tot }}</h3>
                    </div>
                </div>
              </div-->
              @endif

            </div>

            <!--<script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>-->
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            @include('assets.chart-data')
    @endif
    
</main>

@endsection
