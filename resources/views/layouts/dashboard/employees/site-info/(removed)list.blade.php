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

<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Site Info
          @if(Auth::user()->role < 5)
          <b>»</b> {{ $customer->name }}
          @endif
        </h2>
        <div>
    @if(Auth::user()->role < 5)
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#site_infoModal"><i class="fa fa-plus"></i> Add New</button>
    @endif
        <button class="btn btn-primary" onclick="window.location.href='{{ route('employees', ['list', 'cus']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
        </div>
  </div>
    <div class="row mb-4">
        <div class="col-md-2">
            <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
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
                    <th>Brand</th> 
                    <th>Dispenser Type</th>
                    <th>How many 3+0</th>
                    <th>How many 3+1</th>
                    <th>ATG type</th>   
                    <th>Overfill type</th>
                    <th>Spill Bucket brand</th>
                    <th>Vent brand</th>   
                    <th>STP Model</th>
                    <th>Relay brand</th>
                    <th>POS System</th>
                    @if(Auth::user()->role < 5)
                        <th class="text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($site_infos as $site_info)
                    <tr>
                        <td class="align-middle">{{ $site_info->brand }}</td>
                        <td class="align-middle">{{ $site_info->disp_type }}</td>
                        <td class="align-middle">{{ $site_info->h_many_3_0 }}</td>
                        <td class="align-middle">{{ $site_info->h_many_3_1 }}</td>
                        <td class="align-middle">{{ $site_info->atg_type }}</td>
                        <td class="align-middle">{{ $site_info->overfill_type }}</td>
                        <td class="align-middle">{{ $site_info->spill_b_brand }}</td>
                        <td class="align-middle">{{ $site_info->vent_brand }}</td>
                        <td class="align-middle">{{ $site_info->stp_model }}</td>
                        <td class="align-middle">{{ $site_info->relay_brand }}</td>
                        <td class="align-middle">{{ $site_info->pos_system }}</td>
                        @if(Auth::user()->role < 5)
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-primary si-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#site_infoeditModal" data-si-id="{{ $site_info->id }}"><i class="fa fa-edit"></i></button>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $site_infos->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>

<!--popup-->
<div class="modal fade" id="site_infoModal" tabindex="-1" aria-labelledby="site_infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="site_infoModalLabel">Add New Site Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="si-ad" action="{{ url('/dashboard/customers/site-info/add') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="id" name="id" value="{{ $customer->id }}">
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="brand" name="brand">
              </div>
              <div class="col-md-6">
                <label for="disp_type" class="form-label">Dispenser Type</label>
                <input type="text" class="form-control" id="disp_type" name="disp_type">
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="h_many_3_0" class="form-label">How many 3+0</label>
                <input type="text" class="form-control" id="h_many_3_0" name="h_many_3_0">
              </div>
              <div class="col-md-6">
                <label for="h_many_3_1" class="form-label">How many 3+1</label>
                <input type="text" class="form-control" id="h_many_3_1" name="h_many_3_1">
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="atg_type" class="form-label">ATG type</label>
                <input type="text" class="form-control" id="atg_type" name="atg_type">
              </div>
              <div class="col-md-6">
                <label for="overfill_type" class="form-label">Overfill type</label>
                <input type="text" class="form-control" id="overfill_type" name="overfill_type">
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="spill_b_brand" class="form-label">Spill Bucket brand</label>
                <input type="text" class="form-control" id="spill_b_brand" name="spill_b_brand">
              </div>
              <div class="col-md-6">
                <label for="vent_brand" class="form-label">Vent brand</label>
                <input type="text" class="form-control" id="vent_brand" name="vent_brand">
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="stp_model" class="form-label">STP Model</label>
                <input type="text" class="form-control" id="stp_model" name="stp_model">
              </div>
              <div class="col-md-6">
                <label for="relay_brand" class="form-label">Relay brand</label>
                <input type="text" class="form-control" id="relay_brand" name="relay_brand">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-md-12">
                <label for="pos_system" class="form-label">POS System</label>
                <input type="text" class="form-control" id="pos_system" name="pos_system">
              </div>
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="site_infoeditModal" tabindex="-1" aria-labelledby="site_infoeditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="site_infoeditModalLabel">Edit Site Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="si-ad" action="{{ url('/dashboard/customers/site-info/edit') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="si_id" name="si_id" value="">
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="nw_brand" class="form-label">Brand <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nw_brand" name="nw_brand">
              </div>
              <div class="col-md-6">
                <label for="nw_disp_type" class="form-label">Dispenser Type</label>
                <input type="text" class="form-control" id="nw_disp_type" name="nw_disp_type">
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="nw_h_many_3_0" class="form-label">How many 3+0</label>
                <input type="text" class="form-control" id="nw_h_many_3_0" name="nw_h_many_3_0">
              </div>
              <div class="col-md-6">
                <label for="nw_h_many_3_1" class="form-label">How many 3+1</label>
                <input type="text" class="form-control" id="nw_h_many_3_1" name="nw_h_many_3_1">
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="nw_atg_type" class="form-label">ATG type</label>
                <input type="text" class="form-control" id="nw_atg_type" name="nw_atg_type">
              </div>
              <div class="col-md-6">
                <label for="overfill_type" class="form-label">Overfill type</label>
                <input type="text" class="form-control" id="overfill_type" name="overfill_type">
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="nw_spill_b_brand" class="form-label">Spill Bucket brand</label>
                <input type="text" class="form-control" id="nw_spill_b_brand" name="nw_spill_b_brand">
              </div>
              <div class="col-md-6">
                <label for="nw_vent_brand" class="form-label">Vent brand</label>
                <input type="text" class="form-control" id="nw_vent_brand" name="nw_vent_brand">
              </div>
          </div>
          <div class="row mb-2">
              <div class="col-md-6">
                <label for="nw_stp_model" class="form-label">STP Model</label>
                <input type="text" class="form-control" id="nw_stp_model" name="nw_stp_model">
              </div>
              <div class="col-md-6">
                <label for="nw_relay_brand" class="form-label">Relay brand</label>
                <input type="text" class="form-control" id="nw_relay_brand" name="nw_relay_brand">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-md-12">
                <label for="nw_pos_system" class="form-label">POS System</label>
                <input type="text" class="form-control" id="nw_pos_system" name="nw_pos_system">
              </div>
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>

    $(document).on('click', '.si-edit-button', function() {

        $('#si_id').val($(this).data('si-id'));

        var tr = $(this).closest('tr');

        var brand = tr.find('td:eq(0)').text();
        var disp_type = tr.find('td:eq(1)').text();
        var h_many_3_0 = tr.find('td:eq(2)').text();
        var h_many_3_1 = tr.find('td:eq(3)').text();
        var atg_type = tr.find('td:eq(4)').text();
        var overfill_type = tr.find('td:eq(5)').text();
        var spill_b_brand = tr.find('td:eq(6)').text();
        var vent_brand = tr.find('td:eq(7)').text();
        var stp_model = tr.find('td:eq(8)').text();
        var relay_brand = tr.find('td:eq(7)').text();
        var pos_system = tr.find('td:eq(8)').text();

        $('#nw_brand').val(brand);
        $('#nw_disp_type').val(disp_type);
        $('#nw_h_many_3_0').val(h_many_3_0);
        $('#nw_h_many_3_1').val(h_many_3_1);
        $('#nw_atg_type').val(atg_type);
        $('#nw_overfill_type').val(overfill_type);
        $('#nw_spill_b_brand').val(spill_b_brand);
        $('#nw_vent_brand').val(vent_brand);
        $('#nw_stp_model').val(stp_model);
        $('#nw_relay_brand').val(relay_brand);
        $('#nw_pos_system').val(pos_system);
        
    });

</script>