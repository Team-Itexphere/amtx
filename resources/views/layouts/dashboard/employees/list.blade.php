@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<style>
.popovr {
    cursor: pointer;
}
</style>

<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>
            @if($parent && Auth::user()->id != $parent->id)
                Customers <b>»</b> {{ $parent->fac_id }} <b>»</b> Stores
            @elseif($parent && Auth::user()->role == 6)
                Stores
            @else
                @if(isset($_GET['emp']))
                    Employees
                @elseif(isset($_GET['cus']))
                    Customers
                @elseif(isset($_GET['inactive_cus']))
                    Inactive Customers
                @else
                    All Users
                @endif
            @endif
        </h2>
        @if(!$parent && !isset($_GET['inactive_cus']))
            <button class="btn btn-primary" onclick="window.location.href='{{ route('employees', ['add']) }}'"><i class="fa fa-plus"></i> Add New</button>
        @elseif(Auth::user()->role < 6 && !isset($_GET['inactive_cus']))
            <button class="btn btn-primary" onclick="window.location.href='{{ route('employees', ['add', 'parent' => $parent->id]) }}'"><i class="fa fa-plus"></i> Add Store</button>
        @endif
    </div>
    <div class="row mb-4">
        <div class="col-md-2">
            <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
        </div>
        @if(!isset($_GET['cus']) && !isset($_GET['inactive_cus']))
        <div class="col-md-2">
            <select class="form-select rolefilterSelect">
                <option value="" {{ !isset($_GET['role']) ? 'selected' : '' }}>Filter by User Role</option>
                @if(Auth::user()->role == 1)
                <option value="1" {{ isset($_GET['role']) && $_GET['role'] == '1' ? 'selected' : '' }}>Super Admin</option>
                @endif
                @if(Auth::user()->role < 3)
                <option value="2" {{ isset($_GET['role']) && $_GET['role'] == '2' ? 'selected' : '' }}>Admin</option>
                @endif
                <option value="3" {{ isset($_GET['role']) && $_GET['role'] == '3' ? 'selected' : '' }}>Office Staff</option>
                <option value="4" {{ isset($_GET['role']) && $_GET['role'] == '4' ? 'selected' : '' }}>Field Tech Supervisor</option>
                <option value="5" {{ isset($_GET['role']) && $_GET['role'] == '5' ? 'selected' : '' }}>Field Tech</option>
                @if(!isset($_GET['emp']))
                <option value="6" {{ isset($_GET['role']) && $_GET['role'] == '6' ? 'selected' : '' }}>Customer</option>
                @endif
            </select>
        </div>
        @endif
        <div class="col-md-2">
            <button class="btn btn-primary filterButton">Filter</button>
            <button class="btn btn-primary filter-reset">Reset</button>
        </div>

        <form class="col-md-2 ms-auto" method="get" action="{{ url()->current() }}">
            @foreach(request()->query() as $key => $value)
            @if ($key !== 'per_page')
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
            @endforeach
          <div class="form-group d-flex justify-content-end align-items-center">
            <label for="per_page" class="me-1">Items Per Page:</label>
            <select class="form-control w-44" name="per_page" id="per_page" onchange="this.form.submit()" style="max-width: 45px;">
              <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
              <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
              <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
              <option value="40" {{ request('per_page') == 40 ? 'selected' : '' }}>40</option>
              <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
              <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>All</option>
            </select>
          </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="userTable">
            <thead>
                <tr>
                    @if(!isset($_GET['cus']))
                        <th>User Role</th>
                    @endif
                    @if(!isset($_GET['emp']))
                        <th>Facility ID</th>
                    @endif
                    <th>{{ isset($_GET['emp']) ? 'Employee Name' : (isset($_GET['cus']) ? 'Store Name' : 'Name') }}</th>
                    @if(!isset($_GET['emp']))
                        <th>Corporation Name</th>
                    @endif
                    <th>Email</th>
                    <th>Phone No.</th>
                    @if(!isset($_GET['emp']) && isset($_GET['parent']))
                        <th>Store Address</th>
                        <th>Contact Person Name</th>
                        <th>Site info</th>
                        <th>Licenses</th>
                        <th>Compliance Documents</th>
                        <th>Maintenance Logs</th>
                    @endif
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @if($user->role >= Auth::user()->role || Auth::user()->role == 6)
                        <tr>
                            @if(!isset($_GET['cus']))
                            <td class="align-middle">{{ ($user->role == 1 ? 'Super Admin' : ($user->role == 2 ? 'Admin' : ($user->role == 3 ? 'Office Staff' : ($user->role == 4 ? 'Field Tech Supervisor' : ($user->role == 5 ? 'Field Tech' : 'Customer'))))) }}</td>
                            @endif
                            @if(!isset($_GET['emp']))
                            <td class="align-middle">
                                @if(Auth::user()->role == 6)
                                    {{ $user->fac_id }}
                                @else
                                    <a href="{{ route('employees', ['edit' => $user->id, $parent ? 'parent' : '']) }}">{{ $user->fac_id }}</a> 
                                    @if(count($user->stores) > 0)
                                        <span class="popovr" data-bs-content="
                                            @foreach($user->stores as $str)
                                                <a href='{{ route('employees', ['edit' => $str->id, $parent ? 'parent' : '']) }}'>{{ $str->fac_id }}</a><br>
                                            @endforeach
                                        " data-bs-toggle="popover" data-bs-placement="right" title="Stores">
                                            <i class="fa fa-paperclip text-primary"></i>
                                        </span>
                                    @endif
                                @endif
                            </td>
                            @endif
                            <td class="align-middle" style="{{ $user->role == 6 && $user->com_to_inv == 'AMTS' ? 'background: #32cd33;' : ( $user->role == 6 ? 'background: #add9e6;' : '' ) }}">{{ $user->name }}</td>
                            @if(!isset($_GET['emp']))
                            <td class="align-middle">{{ $user->com_name }}</td>
                            @endif
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->phone }}</td>
                            @if(!isset($_GET['emp']) && isset($_GET['parent']))
                                <td class="align-middle">{{ $user->str_addr }}</td>
                                <td class="align-middle">{{ $user->cp_name }}</td>  
                                <td class="align-middle text-center">
                                    @if($user->role == 6 && $user->site_info)
                                        <a href="#" class="site-info-btn" data-bs-toggle="modal" data-bs-target="#site_infoModal" data-id="{{ $user->id }}">View</a>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('cus-licenses', ['id' => $user->id]) }}">View</a>
                                </td> 
                                <td class="align-middle text-center">
                                    <a href="{{ route('comp-docs', ['id' => $user->id]) }}">View</a>
                                </td>
                                <td class="align-middle text-center">
                                    @if(count($user->maintain_logs) > 0)
                                        <a href="{{ route('maintain-logs', ['id' => $user->id]) }}">View</a>
                                    @endif
                                </td>
                            @endif  
                            <td class="align-middle text-center text-nowrap">
                                @if(!isset($_GET['inactive_cus']))
                                    @if(Auth::user()->role == 1)
                                        <button class="delete-item btn btn-danger p-0 px-1" data-action="user/{{ $user->id }}"><i class="fa fa-trash-alt" title="Delete user"></i></button>
                                        @if($user->role == 6)
                                            <button class="btn btn-secondary p-0 px-1" onclick="window.location.href='{{ route('switch_to') }}/{{ $user->id }}'" title="Switch as {{ $user->name }}"><i class="fa fa-sign-in"></i></button>
                                            @if(!$user->parent_id && !$parent)
                                                <button class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('employees', ['list', 'cus', 'parent' => $user->id]) }}'" title="Add a store"><i class="fa fa-institution"></i></button>
                                            @endif
                                        @endif
                                    @endif
                                @else
                                    <button class="restore-item btn btn-danger p-0 px-1" data-action="user/{{ $user->id }}"><i class="fa fa-undo" title="Restore user"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $users->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>

@if(!isset($_GET['emp']) || isset($_GET['cus']))

<!--popup-->
<div class="modal fade" id="site_infoModal" tabindex="-1" aria-labelledby="site_infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="site_infoModalLabel">Site Info <b>»</b> <img src="/img/loader.gif" width="20" class="info-loader" style="display: none;"><span id="info-cus-name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <style>
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
        <table style="border: none; width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td class="bd-bot">Fuel Brand</td>
                <td class="bd-bot" id="inf_fu_brand"></td>
            </tr>
            <tr>
                <td class="bd-bot">Truck stop</td>
                <td class="bd-bot" id="inf_truck_stop"></td>
            </tr>
            <tr>
                <td class="bd-bot">Dispenser Brand</td>
                <td class="bd-bot" id="inf_dis_brand"></td>
            </tr>
            <tr>
                <td class="bd-bot">Dispenser Model</td>
                <td class="bd-bot" id="inf_dis_model"></td>
            </tr>
            <tr>
                <td class="bd-bot">Does it have sumps under dispenser?</td>
                <td class="bd-bot" id="inf_dis_sumps"></td>
            </tr>
            <tr>
                <td class="bd-bot">Sumps Type</td>
                <td class="bd-bot" id="inf_dis_type"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of Vents</td>
                <td class="bd-bot" id="inf_vents_count"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of 3+0 Dispensers</td>
                <td class="bd-bot" id="inf_h_many_3_0"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of 3+1 Dispensers</td>
                <td class="bd-bot" id="inf_h_many_3_1"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of High Flows Dispensers</td>
                <td class="bd-bot" id="inf_h_many_h_flows"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of Tanks</td>
                <td class="bd-bot" id="inf_tanks_count"></td>
            </tr>
            <tr>
                <td class="bd-bot">ATG Brand</td>
                <td class="bd-bot" id="inf_atg_brand"></td>
            </tr>
            <tr>
                <td class="bd-bot">Relay Brand</td>
                <td class="bd-bot" id="inf_relay_brand"></td>
            </tr>
            <tr>
                <td class="bd-bot">POS System</td>
                <td class="bd-bot" id="inf_pos_system"></td>
            </tr>
            </tbody>
        </table>
        <div id="tanks-list-wrap">
            <h5 class="text-center mt-3">Tanks</h5>
            <div id="tanks-list"></div>
        </div>
        <div id="error-message" class="text-danger"></div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        var activePopover = null;

        $('.popovr').popover({
            trigger: 'manual',
            html: true,
        }).on('mouseenter', function () {
            var _this = this;
            activePopover = _this;
            $(this).popover('show');
    
            $(this).data('hovering', true);
    
            $('.popover').on('mouseenter', function () {
            $(_this).data('hovering', true);
            }).on('mouseleave', function () {
            $(_this).data('hovering', false)
            hidePopoverIfNotHovered(_this);
            });
        }).on('mouseleave', function () {
            var _this = this;
            $(this).data('hovering', false);
            hidePopoverIfNotHovered(_this);
        });
    
        function hidePopoverIfNotHovered(element) {
            setTimeout(function () {
            if (!$('.popover:hover').length && !$(element).data('hovering')) {
                $(element).popover('hide');
                if (activePopover === element) {
                activePopover = null;
                }
            }
            }, 100);
        }
        
        
        $('.site-info-btn').click(function() {
            var cusId = $(this).data('id');
            
            $('#info-cus-name').text('');
            $('#inf_fu_brand').text('');
            $('#inf_truck_stop').text('');
            $('#inf_dis_brand').text('');
            $('#inf_dis_model').text('');
            $('#inf_dis_sumps').text('');
            $('#inf_dis_type').text('');
            $('#inf_vents_count').text('');
            $('#inf_h_many_3_0').text('');
            $('#inf_h_many_3_1').text('');
            $('#inf_h_many_h_flows').text('');
            $('#inf_tanks_count').text('');
            $('#inf_atg_brand').text('');
            $('#inf_relay_brand').text('');
            $('#inf_pos_system').text('');
            $('#inf_tanks-list').html('');
            $('#inf_tanks-list-wrap').hide();
            
            $('.info-loader').show();
            
            $.ajax({
                url: '/dashboard/site-info',
                type: 'GET',
                data: { id: cusId },
                success: function(response) {
                    $('.info-loader').hide();
                    $('#info-cus-name').text(response.name ?? 'N/A');
                    $('#inf_fu_brand').text(response.fu_brand ?? 'N/A');
                    $('#inf_truck_stop').text(response.truck_stop ?? 'N/A');
                    $('#inf_dis_brand').text(response.dis_brand ?? 'N/A');
                    $('#inf_dis_model').text(response.dis_model ?? 'N/A');
                    $('#inf_dis_sumps').text(response.dis_sumps ?? 'N/A');
                    $('#inf_dis_type').text(response.dis_type ?? 'N/A');
                    $('#inf_vents_count').text(response.vents_count ?? 'N/A');
                    $('#inf_h_many_3_0').text(response.h_many_3_0 ?? 'N/A');
                    $('#inf_h_many_3_1').text(response.h_many_3_1 ?? 'N/A');
                    $('#inf_h_many_h_flows').text(response.h_many_h_flows ?? 'N/A');
                    $('#inf_tanks_count').text(response.tanks_count ?? 'N/A');
                    $('#inf_atg_brand').text(response.atg_brand ?? 'N/A');
                    $('#inf_relay_brand').text(response.relay_brand ?? 'N/A');
                    $('#inf_pos_system').text(response.pos_system ?? 'N/A');
                    
                    if(response.site_info_tanks){
                        var tanks = response.site_info_tanks;
                        var tanksHtml = '';

                        tanks.forEach(function(tank) {
                            tanksHtml += `
                                <div class="accordion-item">
                                    <h6 class="ac-hd accordion-header">
                                        <a href="#" data-bs-toggle="collapse" data-bs-target="#tank-${tank.id}">
                                            ${tank.tank_name ?? 'Untitled'} <b><i class="fa fa-angle-down"></i></b>
                                        </a>
                                    </h6>
                                    <div id="tank-${tank.id}" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <table style="border: none; margin: auto; width: 98%;">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="bd-bot">Tank ID</td>
                                                    <td class="bd-bot">${tank.fu_type ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Size</td>
                                                    <td class="bd-bot">${tank.size ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Diameter</td>
                                                    <td class="bd-bot">${tank.diameter ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Material</td>
                                                    <td class="bd-bot">${tank.material ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Spill Bucket brand</td>
                                                    <td class="bd-bot">${tank.sb_brand ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Wall</td>
                                                    <td class="bd-bot">${tank.wall_type ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Drain</td>
                                                    <td class="bd-bot">${tank.drain ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Number of Gallon Buckets</td>
                                                    <td class="bd-bot">${tank.h_many_g_bucket ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Inside depth (inches)</td>
                                                    <td class="bd-bot">${tank.in_denpth ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Overfill Prevention</td>
                                                    <td class="bd-bot">${tank.overfill_prev ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Vent Type</td>
                                                    <td class="bd-bot">${tank.vent_type ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">STP manufacturer</td>
                                                    <td class="bd-bot">${tank.stp_manf ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Leak detector</td>
                                                    <td class="bd-bot">${tank.leak_detector ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Does it have STP sumps?</td>
                                                    <td class="bd-bot">${tank.stp_sumps ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Sump Type</td>
                                                    <td class="bd-bot">${tank.stps_type ?? 'N/A'}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    
                        $('#tanks-list').html(tanksHtml);
                        $('#tanks-list-wrap').show();
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred when getting site info: " + error);
                    $('.info-loader').hide();
                }
            });
        });
    });
</script>

@endif

<script>
    $(document).ready(function() {
        $('.filterButton').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            var s = $('.searchInput').val();
            var role = $('.rolefilterSelect').val();
    
            if(s != ''){
                params.set('s', s);
            } else {
                params.delete('s');
            }
            
            if(role){
                params.set('role', role);
            } else {
                params.delete('role');
            }
            
            params.delete('page');
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    
        $('.filter-reset').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            params.delete('s');
            params.delete('role');
            params.delete('page');
            
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    });
</script>
