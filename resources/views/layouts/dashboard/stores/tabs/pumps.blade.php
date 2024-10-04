    <div class="row mb-4">
      <div class="col-md-8 d-flex"> 
        <div class="col-md-4 me-2">
          <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('pmp') ? $_GET['s'] : '' }}">
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pumpModal"><i class="fa fa-plus"></i> Add New</button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered" id="pmp_Table">
        <thead>
            <tr>
                <th>Pump Number</th>
                <th>Dispense Type</th>
                <th>Model</th>
                <th>Payment Type</th>
                <th>Installed Date</th>
                <th>Warranty Info</th>
                <th>Purchase Date</th>
                <th>Purchase From</th>
                <th>Work Orders</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
              $s = isset($_GET['s']) && request()->has('pmp') ? $_GET['s'] : null;
              $pumps = paginateCollection( filterRecords(\App\Models\Pumps::class, null, null, null, $s) );
            @endphp
            @if($pumps)
                @foreach($pumps as $pump)
                    <tr>
                        <td class="align-middle">{{ $pump->pump_number }}</td>
                        <td class="align-middle">{{ $pump->dispense_type }}</td>
                        <td class="align-middle">{{ $pump->model }}</td>
                        <td class="align-middle">{{ $pump->payment_type }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($pump->installed_date)->format('m/d/Y') }}</td>
                        <td class="align-middle">{{ $pump->warr_info }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($pump->purch_date)->format('m/d/Y') }}</td>
                        <td class="align-middle">{{ $pump->purch_from }}</td>
                        <td class="align-middle text-center">
                          @if($pump->Work_orders->count() > 0)
                            <a href="{{ route('work-orders', ['list' => $pump->id, 'type' => 'pump']) }}">Order History</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-primary ft-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#pumpeditModal" data-ft-id="{{ $pump->id }}"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
      </table>
      <br>
      {{ $pumps->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>

<!--popup-->
<div class="modal fade" id="pumpModal" tabindex="-1" aria-labelledby="pumpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="pumpModalLabel">Add New Pump</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="ft-ad" action="{{ url('/dashboard/stores/pumps/add') }}" method="POST">
        @csrf
        <input type="hidden" name="store" value="{{ $store->id }}">
          <div class="mb-3">
            <label for="pump_number" class="form-label">Pump Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="pump_number" name="pump_number" required>
          </div>
          <div class="mb-3">
            <label for="pmp_dispense_type" class="form-label">Type Of Fuel Dispense <span class="text-danger">*</span></label>
            <select class="form-select" id="pmp_dispense_type" name="pmp_dispense_type" required>
                <option>87/89/91 Only</option>
                <option>87/89/91 and Diesel</option>
                <option>Diesel High Flow</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="pmp_model" class="form-label">Model</label>
            <input type="text" class="form-control" id="pmp_model" name="pmp_model">
          </div>
          <div class="mb-3">
            <label for="pmp_payment_type" class="form-label">Payment Type</label>
            <input type="text" class="form-control" id="pmp_payment_type" name="pmp_payment_type">
          </div>
          <div class="mb-3">
            <label for="pmp_installed_date" class="form-label">Installed Date</label>
            <input type="date" class="form-control" id="pmp_installed_date" name="pmp_installed_date">
          </div>
          <div class="mb-3">
            <label for="pmp_warr_info" class="form-label">Warranty Info</label>
            <textarea class="form-control" id="pmp_warr_info" name="pmp_warr_info" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="pmp_purch_date" class="form-label">Purch Date</label>
            <input type="date" class="form-control" id="pmp_purch_date" name="pmp_purch_date">
          </div>
          <div class="mb-3">
            <label for="pmp_purch_from" class="form-label">Purch From</label>
            <input type="text" class="form-control" id="pmp_purch_from" name="pmp_purch_from">
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="pumpeditModal" tabindex="-1" aria-labelledby="pumpeditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="pumpeditModalLabel">Edit Pump</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="ft-ad" action="{{ url('/dashboard/stores/pumps/edit') }}" method="POST">
        @csrf
        <input type="hidden" id="pmp_id" name="pmp_id" value="">
          <div class="mb-3">
            <label for="nw_pump_number" class="form-label">Pump Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nw_pump_number" name="nw_pump_number" required>
          </div>
          <div class="mb-3">
            <label for="nw_pmp_dispense_type" class="form-label">Type Of Fuel Dispense <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_pmp_dispense_type" name="nw_pmp_dispense_type" required>
                <option>87/89/91 Only</option>
                <option>87/89/91 and Diesel</option>
                <option>Diesel High Flow</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nw_pmp_model" class="form-label">Model</label>
            <input type="text" class="form-control" id="nw_pmp_model" name="nw_pmp_model">
          </div>
          <div class="mb-3">
            <label for="nw_pmp_payment_type" class="form-label">Payment Type</label>
            <input type="text" class="form-control" id="nw_pmp_payment_type" name="nw_pmp_payment_type">
          </div>
          <div class="mb-3">
            <label for="nw_pmp_installed_date" class="form-label">Installed Date</label>
            <input type="date" class="form-control" id="nw_pmp_installed_date" name="nw_pmp_installed_date">
          </div>
          <div class="mb-3">
            <label for="nw_pmp_warr_info" class="form-label">Warranty Info</label>
            <textarea class="form-control" id="nw_pmp_warr_info" name="nw_pmp_warr_info" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="nw_pmp_purch_date" class="form-label">Purch Date</label>
            <input type="date" class="form-control" id="nw_pmp_purch_date" name="nw_pmp_purch_date">
          </div>
          <div class="mb-3">
            <label for="nw_pmp_purch_from" class="form-label">Purch From</label>
            <input type="text" class="form-control" id="nw_pmp_purch_from" name="nw_pmp_purch_from">
          </div>
          <div class="mb-3">
            <label for="nw_pmp_store" class="form-label">Store <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_pmp_store" name="nw_pmp_store" required>
                @foreach($stores_all as $single_store)
                    @if(!empty($pump))
                        <option value="{{ $single_store->id }}" {{ $pump->store_id == $single_store->id ? 'selected' : '' }}>{{ $single_store->name }}</option>
                    @else
                        <option value="{{ $single_store->id }}">{{ $single_store->name }}</option>
                    @endif
                @endforeach
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
/*$(document).ready(function() {
    var parametersToRemove = ['em', 'li', 'eq', 'ft', 'wo', 'tnt', 'ut', 'vn'];

    $("#pumps .pagination .page-link").each(function() {
        var href = $(this).attr('href');

        parametersToRemove.forEach(function(param) {
            href = href.replace(new RegExp('[?&]' + param + '=[^&]*'), '');
        });

        if (href.indexOf('pmp') === -1) {
            var separator = (href.indexOf('?') !== -1) ? '&' : '?';
            $(this).attr('href', href + separator + 'pmp');
        }
    });
});*/
</script>

<script>
    $(document).on('click', '.ft-edit-button', function() {

        $('#pmp_id').val($(this).data('ft-id'));
        $('#nw_pmp_store').val({{ $store->id }});

        var tr = $(this).closest('tr');

        var nw_pump_number = tr.find('td:eq(0)').text();
        var nw_pmp_dispense_type = tr.find('td:eq(1)').text();
        var nw_pmp_model = tr.find('td:eq(2)').text();
        var nw_pmp_payment_type = tr.find('td:eq(3)').text();
        var nw_pmp_installed_date = tr.find('td:eq(4)').text();
        var nw_pmp_warr_info = tr.find('td:eq(5)').text();
        var nw_pmp_purch_date = tr.find('td:eq(6)').text();
        var nw_pmp_purch_from = tr.find('td:eq(7)').text();

        $('#nw_pump_number').val(nw_pump_number);
        $('#nw_pmp_dispense_type').val(nw_pmp_dispense_type);
        $('#nw_pmp_model').val(nw_pmp_model);
        $('#nw_pmp_payment_type').val(nw_pmp_payment_type);
        
        var pmp_ins_dateParts = nw_pmp_installed_date.split('/');
        var pmp_ins_month = String(pmp_ins_dateParts[0]).padStart(2, '0');
        var pmp_ins_day = String(pmp_ins_dateParts[1]).padStart(2, '0');
        var pmp_ins_year = parseInt(pmp_ins_dateParts[2]);
        var pmp_ins_formattedDate = pmp_ins_year + '-' + pmp_ins_month + '-' + pmp_ins_day;
        $('#nw_pmp_installed_date').val(pmp_ins_formattedDate);
        
        $('#nw_pmp_warr_info').val(nw_pmp_warr_info);
        
        var pmp_purch_dateParts = nw_pmp_purch_date.split('/');
        var pmp_purch_month = String(pmp_purch_dateParts[0]).padStart(2, '0');
        var pmp_purch_day = String(pmp_purch_dateParts[1]).padStart(2, '0');
        var pmp_purch_year = parseInt(pmp_purch_dateParts[2]);
        var pmp_purch_formattedDate = pmp_purch_year + '-' + pmp_purch_month + '-' + pmp_purch_day;
        $('#nw_pmp_purch_date').val(pmp_purch_formattedDate);
        
        $('#nw_pmp_purch_from').val(nw_pmp_purch_from);
        
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
                    $('#pumpModal').modal('hide');
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

    var nw_pmp_store = document.querySelector('#nw_pmp_store');

    dselect(nw_pmp_store, {
        search: true
    });

</script>