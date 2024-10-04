    <div class="row mb-4">
      <div class="col-md-8 d-flex"> 
        <div class="col-md-4 me-2">
          <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('vn') ? $_GET['s'] : '' }}">
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vendorModal"><i class="fa fa-plus"></i> Add New</button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered" id="vn_Table">
        <thead>
            <tr>
                <th>Vendor Name</th>
                <th>Sales Rep Name</th>
                <th>Contact No</th>
                <th>Delivery</th>
                <th>Payment Type</th>
                <th>Account No</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
              $s = isset($_GET['s']) && request()->has('vn') ? $_GET['s'] : null;
              $vendors = paginateCollection( filterRecords(\App\Models\Vendors::class, null, null, null, $s) );
            @endphp
            @if($vendors)
                @foreach($vendors as $vendor)
                    <tr>
                        <td class="align-middle">{{ $vendor->name }}</td>
                        <td class="align-middle">{{ $vendor->rep_name }}</td>
                        <td class="align-middle">{{ $vendor->phone }}</td>
                        <td class="align-middle">{{ $vendor->delivery }}</td>
                        <td class="align-middle">{{ $vendor->payment_type }}</td>
                        <td class="align-middle">{{ $vendor->acc_num }}</td>
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-primary vn-edit-button p-0 px-1" data-bs-toggle="modal" data-bs-target="#vendoreditModal" data-vn-id="{{ $vendor->id }}"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
      </table>
      <br>
      {{ $vendors->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>

<!--popup-->
<div class="modal fade" id="vendorModal" tabindex="-1" aria-labelledby="vendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="vendorModalLabel">Add New Vendor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="vn-ad" action="{{ url('/dashboard/stores/vendors/add') }}" method="POST">
        @csrf
        <input type="hidden" name="store" value="{{ $store->id }}">
          <div class="mb-3">
            <label for="vn_name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="vn_name" name="vn_name" required>
          </div>
          <div class="mb-3">
            <label for="vn_rep_name" class="form-label">Sales Rep Name</label>
            <input type="text" class="form-control" id="vn_rep_name" name="vn_rep_name" required>
          </div>
          <div class="mb-3">
            <label for="vn_phone" class="form-label">Contact no</label>
            <input type="text" class="form-control" id="vn_phone" name="vn_phone">
          </div>
          <div class="mb-3">
            <label for="vn_delivery" class="form-label">How Often</label>
            <select class="form-select" id="vn_delivery" name="vn_delivery">
                <option>Weekly</option>
                <option>Bi-weekly</option>
                <option>Monthly</option>
                <option>On Call</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="vn_payment_type" class="form-label">Payment Type</label>
            <select class="form-select" id="vn_payment_type" name="vn_payment_type">
                <option>Cash</option>
                <option>EFT</option>
                <option>Check</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="vn_acc_num" class="form-label">Account No</label>
            <input type="text" class="form-control" id="vn_acc_num" name="vn_acc_num">
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
          <div id="error-message" class="text-danger"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup-->
<div class="modal fade" id="vendoreditModal" tabindex="-1" aria-labelledby="vendoreditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="vendoreditModalLabel">Edit Vendor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="vn-ad" action="{{ url('/dashboard/stores/vendors/edit') }}" method="POST">
        @csrf
          <input type="hidden" id="vn_id" name="vn_id" value="">
            <div class="mb-3">
              <label for="nw_vn_name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nw_vn_name" name="nw_vn_name" required>
            </div>
            <div class="mb-3">
              <label for="nw_vn_rep_name" class="form-label">Sales Rep Name</label>
              <input type="text" class="form-control" id="nw_vn_rep_name" name="nw_vn_rep_name">
            </div>
            <div class="mb-3">
              <label for="nw_vn_phone" class="form-label">Contact no</label>
              <input type="text" class="form-control" id="nw_vn_phone" name="nw_vn_phone">
            </div>
            <div class="mb-3">
              <label for="nw_vn_delivery" class="form-label">How Often</label>
              <select class="form-select" id="nw_vn_delivery" name="nw_vn_delivery">
                  <option>Weekly</option>
                  <option>Bi-weekly</option>
                  <option>Monthly</option>
                  <option>On Call</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="nw_vn_payment_type" class="form-label">Payment Type</label>
              <select class="form-select" id="nw_vn_payment_type" name="nw_vn_payment_type">
                  <option>Cash</option>
                  <option>EFT</option>
                  <option>Check</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="nw_vn_acc_num" class="form-label">Account No</label>
              <input type="text" class="form-control" id="nw_vn_acc_num" name="nw_vn_acc_num">
            </div>
            <div class="mb-3">
            <label for="nw_vn_store" class="form-label">Store <span class="text-danger">*</span></label>
            <select class="form-select" id="nw_vn_store" name="nw_vn_store" disabled>
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
$(document).on('click', '.vn-edit-button', function() {

  $('#vn_id').val($(this).data('vn-id'));
  $('#nw_vn_store').val({{ $store->id }});

  var tr = $(this).closest('tr');

  var nw_vn_name = tr.find('td:eq(0)').text();
  var nw_vn_rep_name = tr.find('td:eq(1)').text();
  var nw_vn_phone = tr.find('td:eq(2)').text();
  var nw_vn_delivery = tr.find('td:eq(3)').text();
  var nw_vn_payment_type = tr.find('td:eq(4)').text();
  var nw_vn_acc_num = tr.find('td:eq(5)').text();

  $('#nw_vn_name').val(nw_vn_name);
  $('#nw_vn_rep_name').val(nw_vn_rep_name);
  $('#nw_vn_phone').val(nw_vn_phone);
  $('#nw_vn_delivery').val(nw_vn_delivery);
  $('#nw_vn_payment_type').val(nw_vn_payment_type);
  $('#nw_vn_acc_num').val(nw_vn_acc_num);
        
});
</script>