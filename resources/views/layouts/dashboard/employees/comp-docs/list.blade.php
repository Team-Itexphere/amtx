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
        <h2>Compliance Documents 
          @if(Auth::user()->role < 5)
          <b>»</b> {{ $customer->name }}
          @endif
        </h2>
        <div>
    @if(Auth::user()->role < 5)
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#docModal"><i class="fa fa-plus"></i> Add New</button>
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
                    <th>Doc Name</th> 
                    <th>Type</th>
                    <th>Expire Date</th>
                    <th class="text-center">Document</th>                  
                    @if(Auth::user()->role < 5)
                        <th class="text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $today = \Carbon\Carbon::today();
                    $risk_period = $today->copy()->addDays(29);
                @endphp
                @foreach($docs as $doc)
                    <tr>
                        <td class="align-middle">{{ $doc_types[$doc->name] }}</td>
                        <td class="align-middle">{{ $doc->type }}</td>
                        @php
                            $ex_date = null;
                            $ex_soon = false;
                            if($doc->expire_date){
                                $ex_date = \Carbon\Carbon::parse($doc->expire_date);
                                
                                if($ex_date->isPast() || $ex_date->between($today, $risk_period)) {
                                    $ex_soon = true;
                                }
                                
                                $ex_date = $ex_date->format('m/d/Y');
                            }
                        @endphp
                        <td class="align-middle {{ $ex_soon ? 'text-danger' : '' }}">{{ $ex_date }}</td>
                        <td class="align-middle text-center">
                            @if($doc->doc_path)
                                <a href="{{ url('/customer/docs') }}/{{ $doc->doc_path }}" class="me-2">View</a>
                                <a href="{{ url('/customer/docs') }}/{{ $doc->doc_path }}" target="_blank" download>Download</a>
                            @endif
                        </td>
                        @if(Auth::user()->role < 5)
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-primary doc-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#doceditModal" data-doc-id="{{ $doc->id }}" data-type="{{ $doc->name }}"><i class="fa fa-edit"></i></button>
                            <button type="button" class="delete-item btn btn-danger p-0 px-1" data-action="comp-docs/{{ $doc->id }}"><i class="fa fa-trash-alt"></i></button>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $docs->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>

<!--popup-->
<div class="modal fade" id="docModal" tabindex="-1" aria-labelledby="docModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="docModalLabel">Add New Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="doc-ad" action="{{ url('/dashboard/customers/docs/add') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="id" name="id" value="{{ $customer->id }}">
          <div class="mb-3">
            <label for="name" class="form-label">Test Name <span class="text-danger">*</span></label>
            <select class="form-select" id="name" name="name" required>
                @foreach($doc_types as $key => $type)
                    <option value="{{ $key }}">{{ $type }}</option> 
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="type" class="form-label">Test Type <span class="text-danger">*</span></label>
            <select class="form-select" id="type" name="type" required>
                <option value="Annual">Annual</option>
                <option value="Monthly">Monthly</option>
                <option value="Every 3 years">Every 3 years</option>
                <option value="No Expiry">No Expiry</option>
            </select>
          </div>
          <div class="mb-3 ex-date-ad">
            <label for="expire_date" class="form-label">Expire Date</label>
            <input type="date" class="form-control" id="expire_date" name="expire_date">
          </div>
          <div class="mb-3">
            <label for="doc" class="form-label">Upload File (PDF - Max 20MB)</label>
            <input type="file" class="form-control" id="doc" name="doc" accept=".pdf">
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="doceditModal" tabindex="-1" aria-labelledby="doceditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="doceditModalLabel">Edit Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="doc-ad" action="{{ url('/dashboard/customers/docs/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
          <input type="hidden" id="doc_id" name="doc_id" value="">
          <div class="mb-3">
            <label for="nw_name" class="form-label">Test Name <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_name" name="nw_name" required>
                @foreach($doc_types as $key => $type)
                    <option value="{{ $key }}">{{ $type }}</option> 
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="nw_type" class="form-label">Test Type <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_type" name="nw_type" required>
                <option value="Annual">Annual</option>
                <option value="Monthly">Monthly</option>
                <option value="Every 3 years">Every 3 years</option>
                <option value="No Expiry">No Expiry</option>
            </select>
          </div>
          <div class="mb-3 ex-date-ed">
            <label for="nw_expire_date" class="form-label">Expire Date</label>
            <input type="date" class="form-control" id="nw_expire_date" name="nw_expire_date">
          </div>
          <div class="mb-3">
            <label for="nw_doc" class="form-label">Upload File (PDF - Max 20MB)</label>
            <input type="file" class="form-control" id="nw_doc" name="nw_doc" accept=".pdf">
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>

    $(document).on('click', '.doc-edit-button', function() {

        $('#doc_id').val($(this).data('doc-id'));

        var tr = $(this).closest('tr');

        var nw_type = tr.find('td:eq(1)').text();
        var nw_expire_date = tr.find('td:eq(2)').text();

        $('#nw_name').val($(this).data('type'));
        $('#nw_type').val(nw_type);
        
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
    
    $('#type').change(function() {
        if($(this).val() == 'No Expiry'){
            $('.ex-date-ad').hide();
        } else {
            $('.ex-date-ad').show();
        }
    });
    
    $('#nw_type').change(function() {
        if($(this).val() == 'No Expiry'){
            $('.ex-date-ed').hide();
        } else {
            $('.ex-date-ed').show();
        }
    });

</script>