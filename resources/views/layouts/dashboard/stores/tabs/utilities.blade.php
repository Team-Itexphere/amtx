    <div class="row mb-4">
      <div class="col-md-8 d-flex"> 
        <div class="col-md-4 me-2">
          <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('ut') ? $_GET['s'] : '' }}">
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#utilitieModal"><i class="fa fa-plus"></i> Add New</button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered" id="ut_Table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Service provider</th>
                <th>Avg Monthly Bill</th>
                <th>Contract End Date</th>
                <th>Acc No</th>
                <th>Note</th>
                <th class="text-center">Bill</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
              $s = isset($_GET['s']) && request()->has('ut') ? $_GET['s'] : null;
              $utilities = paginateCollection( filterRecords(\App\Models\Utilities::class, null, null, null, $s) );
            @endphp
            @if($utilities)
                @foreach($utilities as $utilitie)
                    <tr>
                        <td class="align-middle">{{ $utilitie->type }}</td>
                        <td class="align-middle">{{ $utilitie->service_pro }}</td>
                        <td class="align-middle text-end">$<span>{{ $utilitie->avg_m_bill }}</span></td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($utilitie->end_date)->format('m/d/Y') }}</td>
                        <td class="align-middle">{{ $utilitie->acc_num }}</td>
                        <td class="align-middle">{{ $utilitie->note }}</td>
                        <td class="align-middle text-center">
                          @if($utilitie->bill_path)
                            <a href="{{ url('/bills') }}/{{ $utilitie->bill_path }}" target="_blank" download>Download</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                          <button type="button" class="btn btn-primary ut-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#utilitieeditModal" data-ut-id="{{ $utilitie->id }}"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <br>
    {{ $utilities->appends($_GET)->links('pagination::bootstrap-5') }}
  </div>

<!--popup-->
<div class="modal fade" id="utilitieModal" tabindex="-1" aria-labelledby="utilitieModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="utilitieModalLabel">Add New Utility</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="ut-ad" action="{{ url('/dashboard/stores/utilities/add') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="store" value="{{ $store->id }}">
          <div class="mb-3">
            <label for="ut_type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select" id="ut_type" name="ut_type" required>
                <option>Water</option>
                <option>Electricity</option>
                <option>Internet</option>
                <option>Security Service</option>
                <option>Gas</option>
                <option>Trash</option>
                <option>Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="ut_service_pro" class="form-label">Service provider <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="ut_service_pro" name="ut_service_pro" required>
          </div>
          <div class="mb-3">
            <label for="ut_avg_m_bill" class="form-label">Avg Monthly Bill ($)</label>
            <input type="number" class="form-control" id="ut_avg_m_bill" name="ut_avg_m_bill">
          </div>
          <div class="mb-3">
            <label for="ut_end_date" class="form-label">Contract End Date</label>
            <input type="date" class="form-control" id="ut_end_date" name="ut_end_date">
          </div>
          <div class="mb-3">
            <label for="ut_acc_num" class="form-label">Acc. No</label>
            <input type="text" class="form-control" id="ut_acc_num" name="ut_acc_num">
          </div>
          <div class="mb-3">
            <label for="ut_note" class="form-label">Note</label>
            <textarea class="form-control" id="ut_note" name="ut_note" rows="2" maxlength="200"></textarea>
          </div>
          <div class="mb-3">
            <label for="bill_doc" class="form-label">Upload Bill (PDF - Max 20MB)</label>
            <input type="file" class="form-control" id="bill_doc" name="bill_doc" accept=".pdf">
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="utilitieeditModal" tabindex="-1" aria-labelledby="utilitieeditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="utilitieeditModalLabel">Edit Utility</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="nw-ut-ad" action="{{ url('/dashboard/stores/utilities/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
          <input type="hidden" id="ut_id" name="ut_id" value="">
          <div class="mb-3">
            <label for="nw_ut_type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_ut_type" name="nw_ut_type" required>
                <option>Water</option>
                <option>Electricity</option>
                <option>Internet</option>
                <option>Security Service</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nw_ut_service_pro" class="form-label">Service provider <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nw_ut_service_pro" name="nw_ut_service_pro" required>
          </div>
          <div class="mb-3">
            <label for="nw_ut_avg_m_bill" class="form-label">Avg Monthly Bill ($)</label>
            <input type="number" class="form-control" id="nw_ut_avg_m_bill" name="nw_ut_avg_m_bill">
          </div>
          <div class="mb-3">
            <label for="nw_ut_end_date" class="form-label">Contract End Date</label>
            <input type="date" class="form-control" id="nw_ut_end_date" name="nw_ut_end_date">
          </div>
          <div class="mb-3">
            <label for="nw_ut_acc_num" class="form-label">Acc. No</label>
            <input type="text" class="form-control" id="nw_ut_acc_num" name="nw_ut_acc_num">
          </div>
          <div class="mb-3">
            <label for="nw_ut_note" class="form-label">Note</label>
            <textarea class="form-control" id="nw_ut_note" name="nw_ut_note" rows="2" maxlength="200"></textarea>
          </div>
          <div class="mb-3">
            <label for="nw_bill_doc" class="form-label">Upload Bill (PDF - Max 20MB)</label>
            <input type="file" class="form-control" id="nw_bill_doc" name="nw_bill_doc" accept=".pdf">
          </div>
          <div class="mb-3">
            <label for="nw_ut_store" class="form-label">Store <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_ut_store" name="nw_ut_store" disabled>
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
$(document).on('click', '.ut-edit-button', function() {

  $('#ut_id').val($(this).data('ut-id'));
  $('#nw_ut_store').val({{ $store->id }});

  var tr = $(this).closest('tr');

  var nw_ut_type = tr.find('td:eq(0)').text();
  var nw_ut_service_pro = tr.find('td:eq(1)').text();
  var nw_ut_avg_m_bill = tr.find('td:eq(2) span').text();
  var nw_ut_end_date = tr.find('td:eq(3)').text();
  var nw_ut_acc_num = tr.find('td:eq(4)').text();
  var nw_ut_note = tr.find('td:eq(5)').text();

  $('#nw_ut_type').val(nw_ut_type);
  $('#nw_ut_service_pro').val(nw_ut_service_pro);
  $('#nw_ut_avg_m_bill').val(nw_ut_avg_m_bill);
  
  var ut_end_dateParts = nw_ut_end_date.split('/');
  var ut_end_month = String(ut_end_dateParts[0]).padStart(2, '0');
  var ut_end_day = String(ut_end_dateParts[1]).padStart(2, '0');
  var ut_end_year = parseInt(ut_end_dateParts[2]);
  var ut_end_formattedDate = ut_end_year + '-' + ut_end_month + '-' + ut_end_day;
  $('#nw_ut_end_date').val(ut_end_formattedDate);
  
  $('#nw_ut_acc_num').val(nw_ut_acc_num);
  $('#nw_ut_note').val(nw_ut_note);
        
});
</script>