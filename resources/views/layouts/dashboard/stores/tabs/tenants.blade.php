    <div class="row mb-4">
      <div class="col-md-8 d-flex"> 
        <div class="col-md-4 me-2">
          <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('tnt') ? $_GET['s'] : '' }}">
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tenantModal"><i class="fa fa-plus"></i> Add New</button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered" id="tnt_Table">
        <thead>
            <tr>
                <th>Address</th>
                <th>Size (sq ft)</th>
                <th>Rent</th>
                <th>Vacant status</th>
                <th>Name</th>
                <th>Contact No.</th>
                <th class="text-center">Lease agreement</th>
                <th class="text-center">Insurance</th>
                <th class="text-center">Driving License</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
              $s = isset($_GET['s']) && request()->has('tnt') ? $_GET['s'] : null;
              $tenants = paginateCollection( filterRecords(\App\Models\Tenants::class, null, null, null, $s) );
            @endphp
            @if($tenants)
                @foreach($tenants as $tenant)
                    <tr>
                        <td class="align-middle">{{ $tenant->address }}</td>
                        <td class="align-middle text-end">{{ $tenant->size }}</td>
                        <td class="align-middle text-end">$<span>{{ $tenant->rent }}</span></td>
                        <td class="align-middle">{{ $tenant->vacant }}</td>
                        <td class="align-middle">{{ $tenant->name }}</td>
                        <td class="align-middle">{{ $tenant->contact }}</td>
                        <td class="align-middle text-center">
                          @if($tenant->agreement_path)
                            <a href="{{ url('/tenants') }}/{{ $tenant->agreement_path }}" target="_blank" download>Download</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                          @if($tenant->insurance_path)
                            <a href="{{ url('/tenants') }}/{{ $tenant->insurance_path }}" target="_blank" download>Download</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                          @if($tenant->dl_path)
                            <a href="{{ url('/tenants') }}/{{ $tenant->dl_path }}" target="_blank" download>Download</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                          <button type="button" class="btn btn-primary tnt-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#tenanteditModal" data-tnt-id="{{ $tenant->id }}"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <br>
    {{ $tenants->appends($_GET)->links('pagination::bootstrap-5') }}
  </div>

<!--popup-->
<div class="modal fade" id="tenantModal" tabindex="-1" aria-labelledby="tenantModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="tenantModalLabel">Add New Tenant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="nw-tnt-ad" action="{{ url('/dashboard/stores/tenants/add') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="store" value="{{ $store->id }}">
          <div class="row mb-3">
            <div class="col-6">
              <label for="tnt_address" class="form-label">Address <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="tnt_address" name="tnt_address" required>
            </div>
            <div class="col-6">
              <label for="tnt_size" class="form-label">Size (sq ft)</label>
              <input type="number" class="form-control" id="tnt_size" name="tnt_size">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-6">  
              <label for="tnt_rent" class="form-label">Rent ($)</label>
              <input type="number" class="form-control" id="tnt_rent" name="tnt_rent">
            </div>
            <div class="col-6" style="margin-top: 38px;">
              <input class="form-check-input" type="checkbox" id="tnt_vacant_status" name="tnt_vacant_status" value="Yes">
              <label class="form-check-label" for="tnt_vacant_status">Is it vacant?</label>
            </div>
          </div>
          <div class="row mb-3 wrap">
            <div class="col-6">
              <label for="tnt_name" class="form-label">Tenant Name</label>
              <input type="text" class="form-control" id="tnt_name" name="tnt_name">
            </div>
            <div class="col-6">
              <label for="tnt_contact" class="form-label">Contact Number</label>
              <input type="text" class="form-control" id="tnt_contact" name="tnt_contact">
            </div>
            <div class="col-6">
              <label for="tnt_agreement" class="form-label mt-3">Lease Agreement</label>
              <input type="file" class="form-control" id="tnt_agreement" name="tnt_agreement" accept=".pdf">
            </div>
            <div class="col-6">
              <label for="tnt_insurance" class="form-label mt-3">Insurance</label>
              <input type="file" class="form-control" id="tnt_insurance" name="tnt_insurance" accept=".pdf">
            </div>
            <div class="col-6">
              <label for="tnt_dl" class="form-label mt-3">Driving License</label>
              <input type="file" class="form-control" id="tnt_dl" name="tnt_dl" accept=".pdf">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="tenanteditModal" tabindex="-1" aria-labelledby="tenanteditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="tenanteditModalLabel">Edit tenant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="tnt-ad" action="{{ url('/dashboard/stores/tenants/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
          <input type="hidden" id="tnt_id" name="tnt_id" value="">
          <div class="row mb-3">
            <div class="col-6">
              <label for="nw_tnt_address" class="form-label">Address <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nw_tnt_address" name="nw_tnt_address" required>
            </div>
            <div class="col-6">
              <label for="nw_tnt_size" class="form-label">Size (sq ft)</label>
              <input type="number" class="form-control" id="nw_tnt_size" name="nw_tnt_size">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-6">  
              <label for="nw_tnt_rent" class="form-label">Rent ($)</label>
              <input type="number" class="form-control" id="nw_tnt_rent" name="nw_tnt_rent">
            </div>
            <div class="col-6" style="margin-top: 38px;">
              <input class="form-check-input" type="checkbox" id="nw_tnt_vacant_status" name="nw_tnt_vacant_status" value="Yes">
              <label class="form-check-label" for="nw_tnt_vacant_status">Is it vacant?</label>
            </div>
          </div>
          <div class="row mb-3 wrap">
            <div class="col-6">
              <label for="nw_tnt_name" class="form-label">Tenant Name</label>
              <input type="text" class="form-control" id="nw_tnt_name" name="nw_tnt_name">
            </div>
            <div class="col-6">
              <label for="nw_tnt_contact" class="form-label">Contact Number</label>
              <input type="text" class="form-control" id="nw_tnt_contact" name="nw_tnt_contact">
            </div>
            <div class="col-6">
              <label for="nw_tnt_agreement" class="form-label mt-3">Lease Agreement</label>
              <input type="file" class="form-control" id="nw_tnt_agreement" name="nw_tnt_agreement" accept=".pdf">
            </div>
            <div class="col-6">
              <label for="nw_tnt_insurance" class="form-label mt-3">Insurance</label>
              <input type="file" class="form-control" id="nw_tnt_insurance" name="nw_tnt_insurance" accept=".pdf">
            </div>
            <div class="col-6">
              <label for="nw_tnt_dl" class="form-label mt-3">Driving License</label>
              <input type="file" class="form-control" id="nw_tnt_dl" name="nw_tnt_dl" accept=".pdf">
            </div>
          </div>
          <div class="mb-3">
            <label for="nw_tnt_store" class="form-label">Store <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_tnt_store" name="nw_tnt_store" disabled>
                <option value="{{ $store->id }}">{{ $store->name }}</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$('#tnt_vacant_status').change( function() {
  if($('#tnt_vacant_status').is(':checked')){
    $('#tnt_name').closest('.wrap').hide();
  } else {
    $('#tnt_name').closest('.wrap').show();
  }
});

function vacantStatus() {
  if($('#nw_tnt_vacant_status').is(':checked')){
    $('#nw_tnt_name').closest('.wrap').hide();
  } else {
    $('#nw_tnt_name').closest('.wrap').show();
  }
}

$('#nw_tnt_vacant_status').change(vacantStatus);

$(document).on('click', '.tnt-edit-button', function() {

  $('#tnt_id').val($(this).data('tnt-id'));
  $('#nw_tnt_store').val({{ $store->id }});

  var tr = $(this).closest('tr');

  var nw_tnt_address = tr.find('td:eq(0)').text();
  var nw_tnt_size = tr.find('td:eq(1)').text();
  var nw_tnt_rent = tr.find('td:eq(2) span').text();
  var nw_tnt_vacant_status = tr.find('td:eq(3)').text();
  var tnt_name = tr.find('td:eq(4)').text();
  var tnt_contact = tr.find('td:eq(5)').text();

  $('#nw_tnt_address').val(nw_tnt_address);
  $('#nw_tnt_size').val(nw_tnt_size);
  $('#nw_tnt_rent').val(nw_tnt_rent);
  if(nw_tnt_vacant_status == 'Yes'){
    $('#nw_tnt_vacant_status').prop('checked', true);
  }
  vacantStatus();
  $('#nw_tnt_name').val(tnt_name);
  $('#nw_tnt_contact').val(tnt_contact);
        
});
</script>