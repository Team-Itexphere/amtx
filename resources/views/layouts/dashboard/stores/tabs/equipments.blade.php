    <div class="row mb-4">
        <div class="col-md-8 d-flex"> 
          <div class="col-md-4 me-2">
            <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('eq') ? $_GET['s'] : '' }}">
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
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#equipmentModal"><i class="fa fa-plus"></i> Add New</button>
        </div> 
    </div>
    <div class="table-responsive">
      <table class="table table-bordered" id="eq_Table">
        <thead>
            <tr>
                <th>Equipment Name</th>
                <th>Purchased From</th>
                <th>Purchase Date</th>

                @if(Auth::user()->role < 4)
                <th>Cost</th>
                @endif

                <th>Warranty Information</th>
                <th>Serial Number</th>
                <th>Work Orders</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
              $s = isset($_GET['s']) && request()->has('eq') ? $_GET['s'] : null;
              $equipments = paginateCollection( filterRecords(\App\Models\Equipments::class, null, null, null, $s) );
            @endphp
            @if($equipments)
                @foreach($equipments as $equipment)
                    <tr>
                        <td class="align-middle">{{ $equipment->name }}</td>
                        <td class="align-middle">{{ $equipment->purch_from }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($equipment->purch_date)->format('m/d/Y') }}</td>
                        @if(Auth::user()->role < 4)
                        <td class="align-middle text-end">$<span>{{ $equipment->cost }}</span></td>
                        @endif
                        <td class="align-middle">{{ $equipment->warr_info }}</td>
                        <td class="align-middle">{{ $equipment->serial }}</td>
                        <td class="align-middle text-center">
                          @if($equipment->Work_orders->count() > 0)
                            <a href="{{ route('work-orders', ['list' => $equipment->id, 'type' => 'equip']) }}">Order History</a>
                          @endif
                        </td>
                        <td class="align-middle text-center">
                          <button type="button" class="btn btn-primary eq-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#equipmenteditModal" data-eq-id="{{ $equipment->id }}"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <br>
    {{ $equipments->appends($_GET)->links('pagination::bootstrap-5') }}
  </div>

<!--popup-->
<div class="modal fade" id="equipmentModal" tabindex="-1" aria-labelledby="equipmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="equipmentModalLabel">Add New Equipment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="eq-ad" action="{{ url('/dashboard/stores/equipments/add') }}" method="POST">
        @csrf
        <input type="hidden" name="store" value="{{ $store->id }}">
          <div class="mb-3">
            <label for="name" class="form-label">Name *</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="purch_from" class="form-label">Purchased From</label>
            <input type="text" class="form-control" id="purch_from" name="purch_from">
          </div>
          <div class="mb-3">
            <label for="purch_date" class="form-label">Purchase Date</label>
            <input type="date" class="form-control" id="purch_date" name="purch_date">
          </div>
          <div class="mb-3">
            <label for="cost" class="form-label">Cost</label>
            <input type="number" class="form-control" id="cost" name="cost">
          </div>
          <div class="mb-3">
            <label for="warr_info" class="form-label">Warranty Information</label>
            <textarea class="form-control" id="warr_info" name="warr_info" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="serial" class="form-label">Serial Number</label>
            <input type="text" class="form-control" id="serial" name="serial">
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="equipmenteditModal" tabindex="-1" aria-labelledby="equipmenteditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="equipmenteditModalLabel">Edit Equipment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="eq-ad" action="{{ url('/dashboard/stores/equipments/edit') }}" method="POST">
        @csrf
        <input type="hidden" id="eq_id" name="eq_id" value="">
          <div class="mb-3">
            <label for="nw_name" class="form-label">Name *</label>
            <input type="text" class="form-control" id="nw_name" name="nw_name" required>
          </div>
          <div class="mb-3">
            <label for="nw_purch_from" class="form-label">Purchased From</label>
            <input type="text" class="form-control" id="nw_purch_from" name="nw_purch_from">
          </div>
          <div class="mb-3">
            <label for="nw_purch_date" class="form-label">Purchase Date</label>
            <input type="date" class="form-control" id="nw_purch_date" name="nw_purch_date">
          </div>

          @if(Auth::user()->role < 4)
          <div class="mb-3">
            <label for="nw_cost" class="form-label">Cost</label>
            <input type="number" class="form-control" id="nw_cost" name="nw_cost">
          </div>
          @endif

          <div class="mb-3">
            <label for="nw_warr_info" class="form-label">Warranty Information</label>
            <textarea class="form-control" id="nw_warr_info" name="nw_warr_info" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="nw_serial" class="form-label">Serial Number</label>
            <input type="text" class="form-control" id="nw_serial" name="nw_serial">
          </div>
          <div class="mb-3">
            <label for="nw_store" class="form-label">Store <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_store" name="nw_store" required>
                @foreach($stores_all as $single_store)
                    @if(!empty($equipment))
                        <option value="{{ $single_store->id }}" {{ $equipment->store_id == $single_store->id ? 'selected' : '' }}>{{ $single_store->name }}</option>
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
    $(document).on('click', '.eq-edit-button', function() {

        $('#eq_id').val($(this).data('eq-id'));
        $('#nw_store').val({{ $store->id }});

        var tr = $(this).closest('tr');

        var nw_name = tr.find('td:eq(0)').text();
        var nw_purch_from = tr.find('td:eq(1)').text();
        var nw_purch_date = tr.find('td:eq(2)').text();

        @if(Auth::user()->role < 4)
        var nw_cost = tr.find('td:eq(3) span').text();
        var nw_warr_info = tr.find('td:eq(4)').text();
        var nw_serial = tr.find('td:eq(5)').text();
        @else
        var nw_warr_info = tr.find('td:eq(3)').text();
        var nw_serial = tr.find('td:eq(4)').text();
        @endif

        $('#nw_name').val(nw_name);
        $('#nw_purch_from').val(nw_purch_from);
        $('#nw_purch_date').val(nw_purch_date);

        @if(Auth::user()->role < 4)
        $('#nw_cost').val(nw_cost);
        @endif

        $('#nw_warr_info').val(nw_warr_info);
        $('#nw_serial').val(nw_serial);
        
});
</script>

<!--script>
    $(document).ready(function() {
        $('#eq-ad').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    $('#equipmentModal').modal('hide');
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

    var nw_store = document.querySelector('#nw_store');

    dselect(nw_store, {
        search: true
    });

</script>