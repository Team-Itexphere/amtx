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
        <h2>Route List <b>»</b> {{ $route_list->id }}</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('routes') }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <div class="row mb-4">
        <!--div class="col-md-2">
            <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
        </div-->

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
                    <th>FID</th>
                    <th>Store Name</th>
                    <th>Address</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Testing Doc</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ro_locations as $loc_list)
                    <tr>
                        <td class="align-middle">{{ $loc_list->customer->fac_id }}</td>
                        <td class="align-middle">{{ $loc_list->customer->com_name }}</td>
                        <td class="align-middle">{{ $loc_list->customer->str_addr }}</td>
                        <td class="align-middle"></td>
                        <td class="align-middle"></td>
                        <td class="align-middle"></td>
                        <td class="align-middle">{{ $loc_list->amount }}</td>
                        <td class="align-middle"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        
    </div>
</div>


<!--popup view notes>
<div class="modal fade" id="view_noteModal" tabindex="-1" aria-labelledby="view_noteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="view_noteModalLabel">Notes <b>»</b> <span id="note-fac-id"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <style>
            .bd-bot {
                border-bottom: 1px solid #f1f1f1;
            }
        </style>
        <table id="notes-table" style="border: none; width: 100%;">
            <thead>
                <tr>
                    <th class="bd-bot"><b>Date</b></th>
                    <th class="bd-bot"><b>Note</b></th>
                </tr>
            </thead>
            <tbody class="text-start">
                
            </tbody>
        </table>
        <img src="/img/loader.gif" width="20" class="notes-loader m-5" style="display: none;">
        <div id="error-message" class="text-danger"></div>
      </div>
    </div>
  </div>
</div>

<!--popup add notes>
<div class="modal fade" id="add_noteModal" tabindex="-1" aria-labelledby="add_noteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="add_noteModalLabel">Add Note <b>»</b> <span id="add-note-fac-id"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ url('/dashboard/route/add-note') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" name="list_id" value="{{ $route_list->id }}">
            <input type="hidden" class="form-control" id="cus_id" name="cus_id" value="">
            <div class="row mb-3">
                <div class="col-10">
                    <label for="note" class="form-label">Note <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="note">
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
    $(document).ready(function() {
        $('.add-note-btn').click(function() {
            var fac_id = $(this).closest('tr').find('td:eq(0)').text();
            $('#add-note-fac-id').html(fac_id);
            $('#cus_id').val($(this).data('cus-id'));
        });
        
        $('.view-note-btn').click(function() {
            var listId = $(this).data('list-id');
            var cusId = $(this).data('cus-id');
            
            var fac_id = $(this).closest('tr').find('td:eq(0)').text();
            $('#note-fac-id').html(fac_id);
            
            $('#notes-table tbody').html('');
            $('.notes-loader').show();
            
            $.ajax({
                url: '/dashboard/route/notes',
                type: 'GET',
                data: { 
                    list_id: listId,
                    cus_id: cusId,
                },
                success: function(response) {
                    
                    response.forEach(function(note) {
                        $('#notes-table tbody').append('<tr><td class="bd-bot">' + note.date + '</td> <td class="bd-bot">' + note.note + '</td></tr>');
                    });
                    
                    $('.notes-loader').hide();
                },
                error: function(xhr, status, error) {
                    alert("An error occurred when getting notes: " + error);
                    $('.notes-loader').hide();
                }
            });
        });
    });
</script-->