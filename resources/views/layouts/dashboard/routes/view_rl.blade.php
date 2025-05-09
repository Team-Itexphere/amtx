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
        <h2>Route List <b>»</b> {{ $route_list->route->num }}</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ url()->previous() }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="userTable">
            <thead>
                <tr>
                    <th>FID</th>
                    <th>Store Name</th>
                    <th>Address</th>
                    <th class="text-center">Inspection Status</th>
                    <th class="text-center">Payment Status</th>
                    <th class="text-center">Amount ($)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_amount = 0;
                @endphp
                
                @foreach($ro_locations as $loc_list)
                    @php
                        $total_amount += $loc_list->amount ?? 0;
                        $list_id = request('view_rl');
                        $invoice = $loc_list->customer->invoices()->where('route_list_id', $list_id)->first();
                        
                        $paid_amount_arr = $invoice ? json_decode($invoice->paid_amount, true) : [];
                        $paid_amount = 0;
                        if($paid_amount_arr && count($paid_amount_arr) > 0){
                            foreach($paid_amount_arr as $paid){
                                $paid_amount += (float)$paid[0];
                            }
                        }
                    @endphp
                    <tr>
                        <td class="align-middle">{{ $loc_list->customer->fac_id }}</td>
                        <td class="align-middle">{{ $loc_list->customer->name }}</td>
                        <td class="align-middle">{{ $loc_list->customer->str_addr }}</td>
                        <td class="align-middle text-center">{{ $loc_list->status }}</td>
                        <td class="align-middle text-center {{ $invoice?->payment == 'Unpaid' ? 'text-danger' : 'text-success' }}" style="{{ $invoice?->paid_amount && $paid_amount < $invoice?->invoice_items?->sum('amount') ? 'color: #ec6d2b !important;' : '' }}"><b>{{ $invoice && $invoice->paid_amount && $paid_amount < $invoice->invoice_items->sum('amount') ? 'Partially Paid'  : $invoice?->payment }}</b></td>
                        <td class="align-middle text-end">{{ number_format($loc_list->amount ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="align-middle"><b>Total Amount</b></td>
                    <td class="d-none"></td>
                    <td class="d-none"></td>
                    <td class="d-none"></td>
                    <td class="d-none"></td>
                    <td class="align-middle text-end">{{ number_format($total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
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