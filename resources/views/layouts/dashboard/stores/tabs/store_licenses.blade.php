    <div class="row mb-4">
      <div class="col-md-8 d-flex"> 
        <div class="col-md-4 me-2">
          <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('li') ? $_GET['s'] : '' }}">
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#licenseModal"><i class="fa fa-plus"></i> Add New</button>
      </div>        
    </div>
    <div class="table-responsive">
      <table class="table table-bordered" id="li_Table">
        <thead>
            <tr>
                <th>License Name</th>
                <th>Category</th>
                <th>Issue date</th>
                <th>Expire date</th>
                <th>Amount</th>
                <th>Issuer</th>
                <th>Document</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
              $s = isset($_GET['s']) && request()->has('li') ? $_GET['s'] : null;
              $store_licenses = paginateCollection( filterRecords(\App\Models\Store_licenses::class, null, null, null, $s) );
            @endphp
            @if($store_licenses)
                @foreach($store_licenses as $license)
                    <tr>
                        <td class="align-middle">{{ $license->name }}</td>
                        <td class="align-middle">{{ $license->category }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($license->issue_date)->format('m/d/Y') }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($license->expire_date)->format('m/d/Y') }}</td>
                        <td class="align-middle text-end">{{ $license->amount }}</td>
                        <td class="align-middle">{{ $license->issuer }}</td>
                        <td class="align-middle text-center">
                          @if($license->doc_path)
                            <a href="{{ url('/licenses') }}/{{ $license->doc_path }}" target="_blank" download>Download</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                          <button type="button" class="btn btn-primary li-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#licenseeditModal" data-li-id="{{ $license->id }}"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
      </table>
      <br>
      {{ $store_licenses->appends($_GET)->links('pagination::bootstrap-5') }}
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
        <form id="li-ad" action="{{ url('/dashboard/stores/licenses/add') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="store" value="{{ $store->id }}">
          <div class="mb-3">
            <label for="li_name" class="form-label">License Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="li_name" name="li_name" required>
          </div>
          <div class="mb-3">
            <label for="li_category" class="form-label">Category</label>
            <input type="text" class="form-control" id="li_category" name="li_category">
          </div>
          <div class="mb-3">
            <label for="li_issue_date" class="form-label">Issue Date</label>
            <input type="date" class="form-control" id="li_issue_date" name="li_issue_date">
          </div>
          <div class="mb-3">
            <label for="li_expire_date" class="form-label">Expire Date</label>
            <input type="date" class="form-control" id="li_expire_date" name="li_expire_date">
          </div>
          <div class="mb-3">
            <label for="li_amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="li_amount" name="li_amount">
          </div>
          <div class="mb-3">
            <label for="li_issuer" class="form-label">Issuer</label>
            <input type="text" class="form-control" id="li_issuer" name="li_issuer">
          </div>
          <div class="mb-3">
            <label for="license_doc" class="form-label">Upload File (PDF - Max 20MB)</label>
            <input type="file" class="form-control" id="license_doc" name="license_doc" accept=".pdf">
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
        <form id="li-ad" action="{{ url('/dashboard/stores/licenses/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
          <input type="hidden" id="li_id" name="li_id" value="">
          <div class="mb-3">
            <label for="nwli_name" class="form-label">License Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nwli_name" name="nwli_name" required>
          </div>
          <div class="mb-3">
            <label for="nwli_category" class="form-label">Category</label>
            <input type="text" class="form-control" id="nwli_category" name="nwli_category">
          </div>
          <div class="mb-3">
            <label for="nwli_issue_date" class="form-label">Issue Date</label>
            <input type="date" class="form-control" id="nwli_issue_date" name="nwli_issue_date">
          </div>
          <div class="mb-3">
            <label for="nwli_expire_date" class="form-label">Expire Date</label>
            <input type="date" class="form-control" id="nwli_expire_date" name="nwli_expire_date">
          </div>
          <div class="mb-3">
            <label for="nwli_amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="nwli_amount" name="nwli_amount">
          </div>
          <div class="mb-3">
            <label for="nwli_issuer" class="form-label">Issuer</label>
            <input type="text" class="form-control" id="nwli_issuer" name="nwli_issuer">
          </div>
          <div class="mb-3">
            <label for="nwli_store" class="form-label">Store <span class="text-danger">*</span></label>
            <select class="form-select" id="nwli_store" name="nwli_store" disabled>
                <option value="{{ $store->id }}">{{ $store->name }}</option>
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
        $('#nwli_store').val({{ $store->id }});

        var tr = $(this).closest('tr');

        var nwli_name = tr.find('td:eq(0)').text();
        var nwli_category = tr.find('td:eq(1)').text();
        var nwli_issue_date = tr.find('td:eq(2)').text();
         var dateArray = nwli_issue_date.split('/');
         nwli_issue_date = dateArray[2] + '-' + dateArray[0].padStart(2, '0') + '-' + dateArray[1].padStart(2, '0');
        var nwli_expire_date = tr.find('td:eq(3)').text();
         var exdateArray = nwli_expire_date.split('/');
         nwli_expire_date = exdateArray[2] + '-' + exdateArray[0].padStart(2, '0') + '-' + exdateArray[1].padStart(2, '0');
        var nwli_amount = tr.find('td:eq(4)').text();
        var nwli_issuer = tr.find('td:eq(5)').text();

        $('#nwli_name').val(nwli_name);
        $('#nwli_category').val(nwli_category);
        $('#nwli_issue_date').val(nwli_issue_date);
        $('#nwli_expire_date').val(nwli_expire_date);
        $('#nwli_amount').val(nwli_amount);
        $('#nwli_issuer').val(nwli_issuer);
        
});
</script>