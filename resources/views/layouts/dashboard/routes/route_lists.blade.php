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
.unassign {
    padding: 0 2px;
    margin-right: -28px;
}

.unassign img {
    margin-top: -4px;
}
</style>

<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>{{ Auth::user()->role == 5 ? 'Route Lists' : 'Assign Route' }}</h2>
        @if(Auth::user()->role !== 5)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addListModal"><i class="fa fa-plus"></i> Assign List</button>
        @endif
    </div>
    <div class="row mb-4">
        <div class="col-md-2 pe-0">
            <select class="form-select status-filter">
                <option value="0">-- Status --</option>
                <option value="pending" {{ isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="assigned" {{ isset($_GET['status']) && $_GET['status'] == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="accepted" {{ isset($_GET['status']) && $_GET['status'] == 'accepted' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ isset($_GET['status']) && $_GET['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="col-md-2 d-flex pe-0">
           <input type="month" class="form-control date-range month-filter" placeholder="Select Month" value="{{ isset($_GET['month']) ? $_GET['month'] : '' }}">
        </div>

        <div class="col-md-2">
           <button class="btn btn-primary filterButton">Filter</button>
           <button class="btn btn-primary filterReset">Reset</button>
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
                    <th>Route #</th>
                    <th>Route Name</th>
                    <th>Scheduled Date</th>
                    <th>Completed Date</th>
                    <th>Technician</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($route_lists as $route_list)
                    <tr>
                        <td class="align-middle">{{ $route_list->route->num }}</td>
                        <td class="align-middle">{{ $route_list->route->name }}</td>
                        <td class="align-middle">{{ $route_list->start_date ? \Carbon\Carbon::parse($route_list->start_date)->format('m/d/Y') : 'Not Scheduled' }}</td>
                        <td class="align-middle">{{ $route_list->comp_date ? \Carbon\Carbon::parse($route_list->comp_date)->format('m/d/Y') : '' }}</td>
                        <td class="align-middle">{{ $route_list->technicians()->orderBy('id', 'desc')->pluck('name')->implode(', ') ?? '' }}</td>
                        @php
                            $status = $route_list->status == 'pending' || !$route_list->start_date ? 'Pending' : ($route_list->status == 'completed' ? 'Completed' : null);
                            if($status != 'Completed' && $route_list->start_date){
                            
                                $status = 'Not Assigned';
                                if ($route_list->technicians->count() > 0) {
                                    $status = 'Assigned';
                                    if($route_list->testings->count() > 0){
                                        /*$status = 'Accepted';*/
                                        $status = 'In Progress';
                                        $pendingTests = $route_list->testings()->where('status', 'pending')->get();
                                        
                                        if (count($pendingTests) > 0) {
                                        
                                            foreach($pendingTests as $test){
                                                if($test->testing_meta->count() > 0){
                                                    $status = 'In Progress';
                                                    break;
                                                }
                                            }
                                            
                                        }
                                    }
                                    
                                }
                            
                            }
                        @endphp
                        <td class="align-middle text-center">{{ $status }}</td>
                        <td class="align-middle text-center" style="width: 105px;">
                            <button class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('routes', ['view_rl' => $route_list->id]) }}'"><i class="fa fa-eye"></i></button>
                            @if(Auth::user()->role < 4)
                                <button type="button" class="btn btn-primary p-0 px-1 list-edit-button" data-bs-toggle="modal" data-bs-target="#editListModal" data-list-id="{{ $route_list->id }}" data-ro-id="{{ $route_list->route->id }}" data-tech-ids="{{ $route_list->technicians()->pluck('id')->implode(',') }}"><i class="fa fa-edit"></i></button>
                            @endif
                            @if($route_list->technician)
                                <button class="btn btn-danger unassign" data-action="dashboard/routes/unassign/{{ $route_list->id }}"><img src="{{ asset('img') }}/unassign.png" width="20" /></button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $route_lists->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>

<!--popup-->
<div class="modal fade" id="addListModal" tabindex="-1" aria-labelledby="addListModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="addListModalLabel">Assign List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="nw-list-ad" action="{{ url('/dashboard/route/add-rl') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-12">
                    <label for="add-com" class="form-label">Company <span class="text-danger">*</span></label>
                    <select class="form-select" id="add-com">
                        <option value="Both">Both</option>
                        <option value="AMTS">AMTS</option>
                        <option value="PTS">Petro-Tank Solutions</option>
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="route_id" class="form-label">Route <span class="text-danger">*</span></label>
                    <select class="form-select" id="route_id" name="route_id" required>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" data-ref="{{ $route->ro_locations->first()->customer->com_to_inv }}">{{ $route->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label for="tech_id" class="form-label">Technician <span class="text-danger">*</span></label>
                    <select class="form-select" id="tech_ids" name="tech_ids[]" multiple>
                        <option value="0">-- Select Technician --</option>
                        @foreach($technicians_all as $technician)
                            <option value="{{ $technician->id }}" data-ref="{{ $technician->work_for }}">{{ $technician->name }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <!--div class="col-6">
                    <label for="insp_type" class="form-label">Inspection Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="insp_type" name="insp_type" disabled>                
                    </select>
                </div-->
                <div class="col-12">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Assign</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="editListModal" tabindex="-1" aria-labelledby="editListModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="tenanteditModalLabel">Edit List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="edit-list" action="{{ url('/dashboard/route/edit-rl') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="rl_id"  name="rl_id" value="">
            <div class="row mb-3">
                <div class="col-12">
                    <label for="edit-com" class="form-label">Company <span class="text-danger">*</span></label>
                    <select class="form-select" id="edit-com">
                        <option value="Both">Both</option>
                        <option value="AMTS">AMTS</option>
                        <option value="PTS">Petro-Tank Solutions</option>
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="nw_route_id" class="form-label">Route <span class="text-danger">*</span></label>
                    <select class="form-select" id="nw_route_id" name="nw_route_id" required>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" data-ref="{{ $route->ro_locations->first()->customer->com_to_inv }}">{{ $route->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label for="nw_tech_id" class="form-label">Technician <span class="text-danger">*</span></label>
                    <select class="form-select" id="nw_tech_ids" name="nw_tech_ids[]" multiple>
                        <option value="0">- Unassign Technician -</option>
                        @foreach($technicians_all as $technician)
                            <option value="{{ $technician->id }}" data-ref="{{ $technician->work_for }}">{{ $technician->name }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <!--div class="col-6">
                    <label for="nw_insp_type" class="form-label">Inspection Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="nw_insp_type" name="nw_insp_type" disabled>               
                    </select>
                </div-->
                <div class="col-12">
                    <label for="nw_start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="nw_start_date" name="nw_start_date">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {

    $('.filterButton').click(function() {
        let currentUrl = new URL(window.location.href);
        let params = new URLSearchParams(currentUrl.search);

        var status = $('.status-filter').val();
        var month = $('.month-filter').val();

        if(status != 0){
            params.set('status', status);
        } else {
            params.delete('status');
        }
        
        if(month){
            params.set('month', month);
        } else {
            params.delete('month');
        }

        currentUrl.search = params.toString();
        window.location.href = currentUrl.toString();
    });

    $('.filterReset').click(function() {
        let currentUrl = new URL(window.location.href);
        let params = new URLSearchParams(currentUrl.search);

        params.delete('status');
        params.delete('month');

        currentUrl.search = params.toString();
        window.location.href = currentUrl.toString();
    });

});
</script>

<script>
var tech_ids = document.querySelector('#tech_ids');
var route_id = document.querySelector('#route_id');
var nw_tech_ids = document.querySelector('#nw_tech_ids');
var nw_route_id = document.querySelector('#nw_route_id');
dselect(tech_ids, {
    search: true
});
dselect(route_id, {
    search: true
});
dselect(nw_tech_ids, {
    search: true
});
dselect(nw_route_id, {
    search: true
});

$('#add-com').change( function() {
    var add_com = $(this).val();
    $('#nw-list-ad [data-ref="AMTS"], #nw-list-ad [data-ref="PTS"], #nw-list-ad [data-ref="AMTX"], #nw-list-ad [ref="Petro-Tank Solutions"]').hide();
    
    if(add_com == 'Both'){
        $('#nw-list-ad [data-ref="AMTS"], #nw-list-ad [data-ref="PTS"], #nw-list-ad [data-ref="AMTX"], #nw-list-ad [ref="Petro-Tank Solutions"]').show();
    } else if (add_com == 'AMTS'){
        $('#nw-list-ad [data-ref="AMTS"], #nw-list-ad [data-ref="AMTX"]').show();
    } else if (add_com == 'PTS'){
        $('#nw-list-ad [data-ref="PTS"], #nw-list-ad [data-ref="Petro-Tank Solutions"]').show();
    }
});

$('#edit-com').change( function() {
    var edit_com = $(this).val();
    $('#edit-list [data-ref="AMTS"], #edit-list [data-ref="PTS"], #edit-list [data-ref="AMTX"], #edit-list [data-ref="Petro-Tank Solutions"]').hide();
    
    if(edit_com == 'Both'){
        $('#edit-list [data-ref="AMTS"], #edit-list [data-ref="PTS"], #edit-list [data-ref="AMTX"], #edit-list [data-ref="Petro-Tank Solutions"]').show();
    } else if (edit_com == 'AMTS'){
        $('#edit-list [data-ref="AMTS"], #edit-list [data-ref="AMTX"]').show();
    } else if (edit_com == 'PTS'){
        $('#edit-list [data-ref="PTS"], #edit-list [data-ref="Petro-Tank Solutions"]').show();
    }
});

$(document).on('click', '.list-edit-button', function() {

  $('#rl_id').val($(this).data('list-id'));

  var ro_id = $(this).data('ro-id');
  $('#nw_route_id').parent().find('button[data-dselect-value="' + ro_id + '"]').click();
  var tech_ids_arr = $(this).data('tech-ids');
  
  $('#nw_tech_ids').val([0]);
  $('#nw_tech_ids').parent().find('button[data-dselect-value="0"]').click();
  $('#nw_tech_ids').parent().find('.dselect-tag-remove').click();
  
  if (tech_ids_arr) {
    var idsArray = /,/.test(tech_ids_arr) ? tech_ids_arr.split(',') : [tech_ids_arr];
    
    idsArray.forEach(function(id) {
        $('#nw_tech_ids').parent().find('button[data-dselect-value="' + id + '"]').click();
    });
  }
  
  $('#editListModal').click();

  var tr = $(this).closest('tr');

  var nw_start_date = tr.find('td:eq(2)').text();
  var date = new Date(nw_start_date);

  function formatDate(date) {
      var year = date.getFullYear();
      var month = ('0' + (date.getMonth() + 1)).slice(-2);
      var day = ('0' + date.getDate()).slice(-2);
      return year + '-' + month + '-' + day;
  }
  var formattedDate = formatDate(date);
  
  $('#nw_start_date').val(formattedDate);
        
});
</script>