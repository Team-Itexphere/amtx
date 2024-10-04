@extends('layouts.app')


@section('sidebar')

<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar sb">
    @include('layouts.dashboard.sidebar')
</nav>

@endsection


@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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
      @else
        @include('layouts.dashboard.tests.list')
      @endif
    @endif

    @if(Route::is('testing'))
        @include('layouts.dashboard.testing.list')
    @endif

    @if(Route::is('my-profile'))
        @include('layouts.dashboard.employees.my-profile')
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
            </style>

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
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
                    
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
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
                            <h3 class="card-title my-auto"><a href="{{ route('routes', ['list', 'cus', 'cus_type' => 'AMTS']) }}">{{ $tot_am_ro }}</a></h3>
                          </div>
                          <div  class="d-flex justify-content-between align-middle">
                            <p class="fw-semibold d-block my-auto">Total PTS Routes</p>
                            <h3 class="card-title my-auto"><a href="{{ route('routes', ['list', 'cus', 'cus_type' => 'Petro-Tank Solutions']) }}">{{ $tot_pt_ro }}</a></h3>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
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
                      <div class="col-md-{{ auth()->user()->role < 3 ? '12 pb-3' : '8 pb-3' }}" style="overflow-x: auto; overflow-y: hidden;">
                        <div style="width: 750px;">
                          <h5 class="card-header m-0 me-2 pb-3">Monthly Inspection</h5>
                          <div id="totalRevenueChart" class="px-2"></div>
                        </div>
                      </div>
                      @if(auth()->user()->role == 6)
                      <div class="col-md-4 d-flex justify-content-center">
                        <div class="my-auto">
                            <center><h5>Licenses (Expire Soon)</h5></center>
                            @if($ex_licenses)
                                <table id="ex-li-table" style="border: none; width: 100%;">
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
                                        @foreach($ex_licenses as $li)
                                            @if($stores_cnt > 1)
                                                <tr>
                                                    <td class="bd-bot px-1"><b>{{ $li->customer->name }}</b></td>
                                                    <td class="bd-bot px-1"></td>
                                                    <td class="bd-bot px-1"></td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot px-1"></td>
                                                    <td class="bd-bot px-1">{{ $li->name }}</td>
                                                    <td class="bd-bot px-1">{{ \Carbon\Carbon::parse($li->expire_date)->format('m/d/Y') }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="bd-bot px-1">{{ $li->name }}</td>
                                                    <td class="bd-bot px-1">{{ \Carbon\Carbon::parse($li->expire_date)->format('m/d/Y') }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <script>
                                    $(document).ready(function() {
                                        let seen = {};
                                        
                                        $('#ex-li-table tbody tr').each(function() {
                                            let firstColumnText = $(this).find('td:first').text().trim();
                                            
                                            if (seen[firstColumnText] && firstColumnText != '') {
                                                $(this).remove();
                                            } else {
                                                seen[firstColumnText] = true;
                                            }
                                        });
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
                    <div class="col-6" style="padding: 2px 13px;">
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
                    <div class="col-6" style="padding: 2px 13px;">
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

            <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
            @include('assets.chart-data')
    @endif
    
</main>

@endsection
