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
        <h2>Notes</h2>
        <div>
            <button class="btn btn-primary" title="Add Note" data-bs-toggle="modal" data-bs-target="#add_noteModal"><i class="fa fa-pen-alt"></i> Add Note</button>
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
                    <th>Store</th> 
                    <th>Note</th>
                    <th>Date</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notes as $note)
                    <tr>
                        <td class="align-middle"><a href="#" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $note->id }}" data-fac="{{ $note->customer->fac_id }}" data-cus="{{ $note->customer->id }}" data-note="{{ $note->note }}" data-status="{{ $note->status && $note->status == 'Completed' ? 1 : 0 }}">{{ $note->customer->fac_id }} - {{ $note->customer->name }}</a></td>
                        <td class="align-middle">{{ $note->note }}</td>
                        <td class="align-middle">{{ $note->date }}</td>
                        <td class="align-middle text-center">{{ $note->status ?? 'Pending' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $notes->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>

<!--popup add notes-->
<div class="modal fade" id="add_noteModal" tabindex="-1" aria-labelledby="add_noteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="add_noteModalLabel">Add Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ url('/dashboard/route/add-note') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="customer_id" class="form-label">Store <span class="text-danger">*</span></label>
                    <select class="form-select" id="customer_id" name="customer_id">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->fac_id ? '(' . $customer->fac_id . ')' : '' }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="note" class="form-label">Note <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="note"></textarea>
                </div>
            </div>
            <div class="col-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!--popup edit notes-->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Note <b>»</b> <span id="edit-cus-name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ url('/dashboard/route/edit-note') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="note_id" name="note_id" value="">
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="cus_id" class="form-label">Store <span class="text-danger">*</span></label>
                    <select class="form-select" style="background: transparent; margin-bottom: -38px; position: relative;" disabled></select>
                    <select class="form-select" id="cus_id" name="cus_id">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->fac_id ? '(' . $customer->fac_id . ')' : '' }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="note" class="form-label">Note <span class="text-danger">*</span></label>
                    <textarea id="edit-note" class="form-control" name="note"></textarea>
                </div>
            </div>
            <div class="row mb-3 px-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="com-check" name="com_check" value="1">
                    <label class="form-check-label" for="com-check">Complete</label>
                </div>
            </div>
            
            <div class="col-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
var customer_id = document.querySelector('#customer_id');

dselect(customer_id, {
    search: true
});

$(document).ready(function() {
    $('.edit-btn').click(function() {
        var noteId = $(this).data('id');
        var cus = $(this).data('fac');
        var cus_id = $(this).data('cus');
        var note = $(this).data('note');
        var status = $(this).data('status');
        $('#edit-cus-name').html(cus);
        $('#note_id').val(noteId);
        $('#cus_id').val(cus_id);
        $('#edit-note').val(note);
        
        if(status == 1){
            $('#com-check').prop('checked', true);
        } else {
            $('#com-check').prop('checked', false);
        }
    });
});
</script>