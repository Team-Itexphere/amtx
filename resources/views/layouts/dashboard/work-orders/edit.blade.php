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

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Edit Work Order <b>»</b> WO-{{ $work_order->wo_number }}</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('work-orders', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <form class="col-md-{{ Auth::user()->role == 6 ? 6 : 11 }} m-auto" action="{{ url('/dashboard/work_orders/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name="wo_id" value="{{ $work_order->id }}">
        <input type="hidden" name="cu_id" value="{{ $work_order->customer_id }}">
        @if(Auth::user()->role < 6)
            {{--<div class="row mb-3">
                <div class="col-md-3">
                    <label for="cu_id" class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select" id="cu_id" name="cu_id">
                        @foreach($customers_all as $customer)
                            @if($work_order->customer_id == $customer->id)
                                <option value="{{ $customer->id }}" {{ $work_order->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
            </div>--}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="Pending" {{ $work_order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ $work_order->status == 'Completed' ? 'selected' : '' }}>Completed</option>  
                    </select> 
                </div> 
                <div class="col-md-6 tech">
                    <label for="tech_id" class="form-label">Technician</label>
                    <select class="form-select" id="tech_id" name="tech_id">
                        <option value="">-- Select Technician --</option>
                        @foreach($technicians_all as $technician)
                            <option value="{{ $technician->id }}" data-ref="{{ $technician->work_for }}" {{ $work_order->tech_id == $technician->id ? 'selected' : '' }}>{{ $technician->name }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="date" class="form-label" >Date</label>
                    <input type="date" class="form-control" id="date" name="date"value="{{ $work_order->date }}">
                </div>
                <div class="col-md-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control" id="time" name="time" value="{{ $work_order->time }}">
                </div>
                <div class="col-md-6">
                    <label for="priority" class="form-label">Priority</label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="High" {{ $work_order->priority == 'High' ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ $work_order->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low" {{ $work_order->priority == 'Low' ? 'selected' : '' }}>Low</option>
                    </select> 
                </div>
            </div>  
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="comment" class="form-label">Office Comment</label>
                    <textarea class="form-control" rows="3" id="comment" name="comment"></textarea>
                </div>
                <!--<div class="col-md-6">
                    <input class="form-check-input" type="checkbox" id="invoiced" name="invoiced" value="1" {{ $work_order->invoiced ? 'checked' : '' }}>
                    <label class="form-check-label" for="invoiced">Invoiced</label>
                </div>
                div class="col-md-6">
                    <label for="description" class="form-label">Customer's Comment</label>
                    <textarea class="form-control" rows="3" id="description" name="description" readonly></textarea>
                </div-->
            </div>
        @elseif(Auth::user()->role == 6)
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cu_id" class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select" id="cu_id" name="cu_id">
                        @foreach($customers_all as $customer)
                            @if($work_order->customer_id == $customer->id)
                                <option value="{{ $customer->id }}" {{ $work_order->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="date" class="form-label" >Date</label>
                    <input type="date" class="form-control" id="date" name="date"value="{{ $work_order->date }}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control" id="time" name="time" value="{{ $work_order->time }}" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="description" class="form-label">Comment</label>
                    <textarea class="form-control" rows="3" id="description" name="description"></textarea>
                </div>
            </div>
        @endif        
        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>

<div class="container mt-5">
    <h4 class="mb-4 text-center">Work order history</h4>
    <div class="table-responsive col-md-11 m-auto">
        <table class="table table-bordered" id="comnt-table">
            <thead>
                <tr>
                    <th>Commented By</th> 
                    <th class="text-center">Date</th>
                    <th>Comment</th>
                    <th class="text-center">Images</th>
                </tr>
            </thead>
            <tbody id="comnt-tbody">
                @foreach($work_order->service_calls as $call)
                    <tr data-order="{{ $call->created_at->format('Y-m-d H:i') }}">
                        <td class="align-middle">{{ $call->technician ? $work_order->technician->name : '' }}</td>
                        <td class="align-middle text-center">{{ $call->created_at->format('m/d/Y') }}</td>
                        <td class="align-middle">{{ $call->comment }}</td>
                        <td class="align-middle text-center">
                            @if($call->images)
                                <a href="#" class="service-call-btn" data-bs-toggle="modal" data-bs-target="#service_callModal" data-id="{{ $call->id }}">View</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                
                @php
                    $office_cmnts = $work_order->comment ? json_decode($work_order->comment, true) : [];
                @endphp
                
                @foreach($office_cmnts as $comment)
                    <tr data-order="{{ $comment[1] ? \Carbon\Carbon::parse($comment[1])->format('Y-m-d H:i') : '' }}">
                        <td class="align-middle">{{ isset($comment[2]) ? $comment[2] : '' }}</td>
                        <td class="align-middle text-center">{{ $comment[1] ? \Carbon\Carbon::parse($comment[1])->format('m/d/Y') : '' }}</td>
                        <td class="align-middle">{{ $comment[0] }}</td>
                        <td class="align-middle text-center"></td>
                    </tr>
                @endforeach
                
                @php
                    $store_cmnts = $work_order->description ? json_decode($work_order->description, true) : [];
                @endphp

                @foreach($store_cmnts as $comment)
                    <tr data-order="{{ $comment[1] ? \Carbon\Carbon::parse($comment[1])->format('Y-m-d H:i') : '' }}">
                        <td class="align-middle">{{ isset($comment[2]) ? $comment[2] : '' }}</td>
                        <td class="align-middle text-center">{{ $comment[1] ? \Carbon\Carbon::parse($comment[1])->format('m/d/Y') : '' }}</td>
                        <td class="align-middle">{{ $comment[0] }}</td>
                        <td class="align-middle text-center"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!--popup-->
<div class="modal fade" id="service_callModal" tabindex="-1" aria-labelledby="service_callModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="service_callModalLabel">Images</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div id="sc-img-cont" class="d-flex flex-wrap justify-content-center"></div>
        
        <img src="/img/loader.gif" width="20" class="pic-loader" style="display: none;">
        <div id="error-message" class="text-danger"></div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        function customSort() {
            let rows = $("#comnt-tbody tr").get();
            
            rows.sort(function (a, b) {
                let dateA = new Date($(a).attr("data-order"));
                let dateB = new Date($(b).attr("data-order"));

                return dateB - dateA;
            });

            $("#comnt-tbody").html(rows);
        }

        customSort();
        
        $('.service-call-btn').click(function() {
            var callId = $(this).data('id');
            
            $('#order-num').text('');
            $('#sc-img-cont img').remove();
            
            $('.pic-loader').show();
            
            $.ajax({
                url: '/dashboard/work-order/service-call/images',
                type: 'GET',
                data: { call_id: callId },
                success: function(response) {
                    $('#order-num').text(response.wo_number);
                    
                    var imgs = response.images;
                    imgs.forEach(function(img) {
                        $('#sc-img-cont').append('<img class="col-4 p-2" src="' + img + '">');
                    });
                    
                    $('.pic-loader').hide();
                },
                error: function(xhr, status, error) {
                    alert("An error occurred when getting images: " + error);
                    $('.pic-loader').hide();
                }
            });
        });
        
        $('#status').change(function() {
            if($(this).val() == 'Completed'){
                $('#tech_id, #priority, #comment').prop('required', true);
            }
        });
    });
</script>


<script>

    $(document).ready(function() {
        /*var cu_id = document.querySelector('#cu_id');
        var tech_id = document.querySelector('#tech_id');
        var fleet_id = document.querySelector('#fleet_id');
    
        dselect(cu_id, {
            search: true
        });
    
        dselect(tech_id, {
            search: true
        });
    
        dselect(fleet_id, {
            search: true
        });*/
        $('#cu_id').before('<select class="form-select" style="background: transparent; margin-bottom: -38px; position: relative;" disabled></select>');
        @if(Auth::user()->role == 6)
            $('select').before('<select class="form-select" style="background: transparent; margin-bottom: -38px; position: relative;" disabled></select>');
        @endif
    });

@if(Auth::user()->role < 6)

function update_com() {
    var com = "{{ $work_order->customer->com_to_inv == 'AMTS' ? 'AMTS' : ($work_order->customer->com_to_inv ? 'PTS' : 'Both') }}";
    $('.tech [data-ref="AMTS"], .tech [data-ref="PTS"], .tech [data-ref="AMTX"], .tech [data-ref="Petro-Tank Solutions"]').hide();
    
    if(com == 'Both' || com === ''){
        $('.tech [data-ref="AMTS"], .tech [data-ref="PTS"], .tech [data-ref="AMTX"], .tech [data-ref="Petro-Tank Solutions"]').show();
    } else if (com == 'AMTS'){
        $('.tech [data-ref="AMTS"], .tech [data-ref="AMTX"]').show();
    } else if (com == 'PTS'){
        $('.tech [data-ref="PTS"], .tech [data-ref="Petro-Tank Solutions"]').show();
    }
};
update_com();

@endif
</script>