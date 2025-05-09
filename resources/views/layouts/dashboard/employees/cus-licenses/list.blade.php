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
        <h2>Licenses 
          @if(Auth::user()->role < 5)
          <b>»</b> {{ $customer->name }}
          @endif
        </h2>
        <div>
    @if(Auth::user()->role < 5 || Auth::user()->role == 6)
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#licenseModal"><i class="fa fa-plus"></i> Add New</button>
    @endif
        <button class="btn btn-primary" onclick="window.location.href='{{ url()->previous() }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
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
                    <th>Type</th>
                    <th>Agency</th>
                    <th>Expire Date</th>
                    <th class="text-center">Document</th>                  
                    @if(Auth::user()->role < 4)
                        <th class="text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $today = \Carbon\Carbon::today();
                    $risk_period = $today->copy()->addDays(29);
                @endphp
                @foreach($licenses as $license)
                    <tr>
                        <td class="align-middle">{{ $license->type }}</td>
                        <td class="align-middle">{{ $license->agency }}</td>
                        @php
                            $ex_date = null;
                            $ex_soon = false;
                            if($license->expire_date){
                                $ex_date = \Carbon\Carbon::parse($license->expire_date);
                                
                                if($ex_date->isPast() || $ex_date->between($today, $risk_period)) {
                                    $ex_soon = true;
                                }
                                
                                $ex_date = $ex_date->format('m/d/Y');
                            }
                        @endphp
                        <td class="align-middle {{ $ex_soon ? 'text-danger' : '' }}">{{ $ex_date }}</td>
                        <td class="align-middle text-center">
                            @if($license->doc_path)
                                <a href="{{ url('/customer/licenses') }}/{{ $license->doc_path }}" class="me-2">View</a>
                                <a href="{{ url('/customer/licenses') }}/{{ $license->doc_path }}" target="_blank" download>Download</a>
                            @endif
                        </td>
                        @if(Auth::user()->role < 4)
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-primary li-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#licenseeditModal" data-li-id="{{ $license->id }}"><i class="fa fa-edit"></i></button>
                            @if(Auth::user()->role == 1)
                                <button class="delete-item btn btn-danger p-0 px-1" data-action="cus-license/{{ $license->id }}"><i class="fa fa-trash-alt" title="Delete license"></i></button>
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $licenses->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>

<!--popup-->
<div class="modal fade" id="licenseModal" tabindex="-1" aria-labelledby="licenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="licenseModalLabel">Add New License</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="li-ad" action="{{ url('/dashboard/customers/licenses/add') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="id" name="id" value="{{ $customer->id }}">
          <div class="mb-3">
            <label for="type" class="form-label">License Type <span class="text-danger">*</span></label>
            <select class="form-select" id="type" name="type" required>
                <option value="Delivery Certificate">Delivery Certificate</option>
                <option value="Motor fuel Metering and Quality">Motor fuel Metering and Quality</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="expire_date" class="form-label">Expire Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="expire_date" name="expire_date" required>
          </div>
          <div class="mb-3">
            <label for="agency" class="form-label">Agency <span class="text-danger">*</span></label>
            <select class="form-select" id="agency" name="agency" required>
                <option value="TCEQ">TCEQ</option>
                <option value="TDLR">TDLR</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="license_doc" class="form-label">Upload File (PDF - Max 20MB) <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="license_doc" name="license_doc" accept=".pdf" required>
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="licenseeditModal" tabindex="-1" aria-labelledby="licenseeditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="licenseeditModalLabel">Edit License</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="li-ad" action="{{ url('/dashboard/customers/licenses/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
          <input type="hidden" id="li_id" name="li_id" value="">
          <div class="mb-3">
            <label for="nw_type" class="form-label">License Type <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_type" name="nw_type" required>
                <option value="Delivery Certificate">Delivery Certificate</option>
                <option value="Motor fuel Metering and Quality">Motor fuel Metering and Quality</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nw_expire_date" class="form-label">Expire Date</label>
            <input type="date" class="form-control" id="nw_expire_date" name="nw_expire_date">
          </div>
          <div class="mb-3">
            <label for="nw_agency" class="form-label">Agency <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_agency" name="nw_agency" required>
                <option value="TCEQ">TCEQ</option>
                <option value="TDLR">TDLR</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nw_license_doc" class="form-label">Upload File (PDF - Max 20MB)</label>
            <input type="file" class="form-control" id="nw_license_doc" name="nw_license_doc" accept=".pdf">
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>

    $(document).on('click', '.li-edit-button', function() {

        $('#li_id').val($(this).data('li-id'));

        var tr = $(this).closest('tr');

        var nw_type = tr.find('td:eq(0)').text();
        var nw_agency = tr.find('td:eq(1)').text();
        var nw_expire_date = tr.find('td:eq(2)').text();

        $('#nw_type').val(nw_type);
        $('#nw_agency').val(nw_agency);
        
        var date = new Date(nw_expire_date);
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }
        var formattedDate = formatDate(date);
        $('#nw_expire_date').val(formattedDate);
    });

</script>