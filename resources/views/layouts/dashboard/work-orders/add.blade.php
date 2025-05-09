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
        <h2>Add New Work Order</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('work-orders', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <form class="col-md-{{ Auth::user()->role == 6 ? 6 : 11 }} m-auto" action="{{ url('/dashboard/work_orders/add') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if(Auth::user()->role < 6)
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="com" class="form-label">Company <span class="text-danger">*</span></label>
                    <select class="form-select" id="com">
                        <option value="Both">Both</option>
                        <option value="AMTS">AMTS</option>
                        <option value="PTS">Petro-Tank Solutions</option>
                    </select> 
                </div>
                <div class="col-md-3">
                    <label for="cu_id" class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select" id="cu_id" name="cu_id">
                        @foreach($customers_all as $customer)
                            <option value="{{ $customer->id }}" data-ref="{{ $customer->com_to_inv }}">{{ $customer->name }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6" style="display: none;">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>                 
                    </select> 
                </div> 
                <div class="col-md-6">
                    <label for="tech_id" class="form-label">Technician</label>
                    <select class="form-select" id="tech_id" name="tech_id">
                        <option value="">-- Select Technician --</option>
                        @if(Auth::user()->role > 3)
                            <option value="{{ Auth::user()->id }}" data-ref="{{ Auth::user()->work_for }}" selected>{{ Auth::user()->name }}</option>
                        @else
                            @foreach($technicians_all as $technician)
                                 <option value="{{ $technician->id }}" data-ref="{{ $technician->work_for }}">{{ $technician->name }}</option>
                            @endforeach
                        @endif
                    </select> 
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">Date Created</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="time" class="form-label">Time Created</label>
                    <input type="time" class="form-control" id="time" name="time" value="{{ now()->format('H:i') }}">
                </div>
                <div class="col-md-6">
                    <label for="priority" class="form-label">Priority</label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="High">High</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="Low">Low</option>
                    </select> 
                </div>
            </div> 
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="comment" class="form-label">Office Comment</label>
                    <textarea class="form-control" rows="3" id="comment" name="comment"></textarea>
                </div>
                <!--<div class="col-md-6">
                    <input class="form-check-input" type="checkbox" id="invoiced" name="invoiced" value="1">
                    <label class="form-check-label" for="invoiced">Invoiced</label>
                </div>
                <div class="col-md-6">
                    <label for="description" class="form-label">Customer's Comment <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="3" id="description" name="description" disabled></textarea>
                </div-->
            </div>
        @elseif(Auth::user()->role == 6)
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cu_id" class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select" id="cu_id" name="cu_id">
                        @foreach($customers_all as $customer)
                            <option value="{{ $customer->id }}" {{ Auth::user()->id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control" id="time" name="time" value="{{ now()->format('H:i') }}">
                </div>
            </div> 
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" rows="3" id="description" name="description"></textarea>
                </div>
            </div>
        @endif         
        <button type="submit" class="btn btn-primary mt-2">Create</button>
    </form>
</div>


<script>

    var cu_id = document.querySelector('#cu_id');
    dselect(cu_id, {
        search: true
    });
    
    @if(Auth::user()->role < 6)
    var tech_id = document.querySelector('#tech_id');

    dselect(tech_id, {
        search: true
    });
    @endif

    
@if(Auth::user()->role < 6)

function update_com() {
    var com = $('#com').val();
    $('.dropdown-item[data-ref="AMTS"], .dropdown-item[data-ref="PTS"], .dropdown-item[data-ref="AMTX"], .dropdown-item[data-ref="Petro-Tank Solutions"]').hide();
    
    if(com == 'Both'){
        $('.dropdown-item[data-ref="AMTS"], .dropdown-item[data-ref="PTS"], .dropdown-item[data-ref="AMTX"], .dropdown-item[data-ref="Petro-Tank Solutions"]').show();
    } else if (com == 'AMTS'){
        $('.dropdown-item[data-ref="AMTS"], .dropdown-item[data-ref="AMTX"]').show();
    } else if (com == 'PTS'){
        $('.dropdown-item[data-ref="PTS"], .dropdown-item[data-ref="Petro-Tank Solutions"]').show();
    }
};
update_com();

$('#com').change(update_com);

@endif
</script>