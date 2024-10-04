    <div class="row mb-4">
      <div class="col-md-8 d-flex"> 
        <div class="col-md-4 me-2">
          <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('ft') ? $_GET['s'] : '' }}">
        </div>
        <div class="col-md-3">
          <button class="btn btn-primary filterButton">Filter</button>
          <button class="btn btn-primary filter-reset">Reset</button>
        </div>
      </div>
          
      <div class="col-md-4 d-flex">            
        <form class="col-md-7 ms-auto me-2" method="get" action="{{ url()->current() }}">
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fuel_tankModal"><i class="fa fa-plus"></i> Add New</button>
      </div>        
    </div>
 
    <div class="table-responsive">
      <table class="table table-bordered" id="ft_Table">
        <thead>
            <tr>
                <th>Tank Name</th>
                <th>Type</th>
                <th>Capacity</th>
                <th>Installation Date</th>
                <th>Installed By</th>
                <th>Work Orders</th>
                <th>Tank Monitor Chart</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
              $s = isset($_GET['s']) && request()->has('ft') ? $_GET['s'] : null;
              $fuel_tanks = paginateCollection( filterRecords(\App\Models\Fuel_tanks::class, null, null, null, $s) );
            @endphp
            @if($fuel_tanks)
                @foreach($fuel_tanks as $fuel_tank)
                    <tr>
                        <td class="align-middle">{{ $fuel_tank->name }}</td>
                        <td class="align-middle">{{ $fuel_tank->type }}</td>
                        <td class="align-middle text-end">{{ $fuel_tank->capacity }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($fuel_tank->installation_date)->format('m/d/Y') }}</td>
                        <td class="align-middle">{{ $fuel_tank->installed_by }}</td>
                        <td class="align-middle text-center">
                          @if($fuel_tank->Work_orders->count() > 0)
                            <a href="{{ route('work-orders', ['list' => $fuel_tank->id, 'type' => 'f_tank']) }}">Order History</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                          @if($fuel_tank->tmc_path)
                            <a href="{{ url('/tank-monitor-charts') }}/{{ $fuel_tank->tmc_path }}" target="_blank" download>Download</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-primary ft-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#fuel_tankeditModal" data-ft-id="{{ $fuel_tank->id }}"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
      </table>
      <br>
      {{ $fuel_tanks->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>

<!--popup-->
<div class="modal fade" id="fuel_tankModal" tabindex="-1" aria-labelledby="fuel_tankModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="fuel_tankModalLabel">Add New Fuel Tank</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="ft-ad" action="{{ url('/dashboard/stores/fuel_tanks/add') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="store" value="{{ $store->id }}">
          <div class="mb-3">
            <label for="ft_name" class="form-label">Tank Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="ft_name" name="ft_name" required>
          </div>
          <div class="mb-3">
            <label for="ft_type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select" id="ft_type" name="ft_type" required>
                <option>Regular</option>
                <option>Diesel</option>
                <option>Super</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="ft_capacity" class="form-label">Capacity</label>
            <input type="number" class="form-control" id="ft_capacity" name="ft_capacity">
          </div>
          <div class="mb-3">
            <label for="ft_installation_date" class="form-label">Installation Date</label>
            <input type="date" class="form-control" id="ft_installation_date" name="ft_installation_date">
          </div>
          <div class="mb-3">
            <label for="ft_installed_by" class="form-label">Installed By</label>
            <input type="text" class="form-control" id="ft_installed_by" name="ft_installed_by">
          </div>
          <div class="mb-3">
            <label for="tmc_doc" class="form-label">Upload TMC (PDF - Max 20MB)</label>
            <input type="file" class="form-control" id="tmc_doc" name="tmc_doc" accept=".pdf">
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="fuel_tankeditModal" tabindex="-1" aria-labelledby="fuel_tankeditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="fuel_tankeditModalLabel">Edit Fuel Tank</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="ft-ad" action="{{ url('/dashboard/stores/fuel_tanks/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="ft_id" name="ft_id" value="">
          <div class="mb-3">
            <label for="nw_ft_name" class="form-label">Tank Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nw_ft_name" name="nw_ft_name" required>
          </div>
          <div class="mb-3">
            <label for="nw_ft_type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_ft_type" name="nw_ft_type" required>
                <option>Regular</option>
                <option>Diesel</option>
                <option>Super</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nw_ft_capacity" class="form-label">Capacity</label>
            <input type="number" class="form-control" id="nw_ft_capacity" name="nw_ft_capacity">
          </div>
          <div class="mb-3">
            <label for="nw_ft_installation_date" class="form-label">Installation Date</label>
            <input type="date" class="form-control" id="nw_ft_installation_date" name="nw_ft_installation_date">
          </div>
          <div class="mb-3">
            <label for="nw_ft_installed_by" class="form-label">Installed By</label>
            <input type="text" class="form-control" id="nw_ft_installed_by" name="nw_ft_installed_by">
          </div>
          <div class="mb-3">
            <label for="nw_ft_store" class="form-label">Store <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_ft_store" name="nw_ft_store" required>
                @foreach($stores_all as $single_store)
                    @if(!empty($fuel_tank))
                        <option value="{{ $single_store->id }}" {{ $fuel_tank->store_id == $single_store->id ? 'selected' : '' }}>{{ $single_store->name }}</option>
                    @else
                        <option value="{{ $single_store->id }}">{{ $single_store->name }}</option>
                    @endif
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="nw_tmc_doc" class="form-label">Upload TMC (PDF - Max 20MB)</label>
            <input type="file" class="form-control" id="nw_tmc_doc" name="nw_tmc_doc" accept=".pdf">
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).on('click', '.ft-edit-button', function() {

        $('#ft_id').val($(this).data('ft-id'));
        $('#nw_ft_store').val({{ $store->id }});

        var tr = $(this).closest('tr');

        var nw_ft_name = tr.find('td:eq(0)').text();
        var nw_ft_type = tr.find('td:eq(1)').text();
        var nw_ft_capacity = tr.find('td:eq(2)').text();
        var nw_ft_installation_date = tr.find('td:eq(3)').text();
        var nw_ft_installed_by = tr.find('td:eq(4)').text();

        $('#nw_ft_name').val(nw_ft_name);
        $('#nw_ft_type').val(nw_ft_type);
        $('#nw_ft_capacity').val(nw_ft_capacity);
        
        var ins_dateParts = nw_ft_installation_date.split('/');
        var ins_month = String(ins_dateParts[0]).padStart(2, '0');
        var ins_day = String(ins_dateParts[1]).padStart(2, '0');
        var ins_year = parseInt(ins_dateParts[2]);
        var ins_formattedDate = ins_year + '-' + ins_month + '-' + ins_day;
        $('#nw_ft_installation_date').val(ins_formattedDate);
        
        $('#nw_ft_installed_by').val(nw_ft_installed_by);
        
});
</script>

<!--script>
    $(document).ready(function() {
        $('#ft-ad').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    $('#fuel_tankModal').modal('hide');
                },
                error: function(error) {
                    var errors = error.responseJSON.errors;
                    $('#error-message').text(errors.name[0]);
                }
            });
        });
    });
</script-->

<script>

    var nw_ft_store = document.querySelector('#nw_ft_store');

    dselect(nw_ft_store, {
        search: true
    });

</script>