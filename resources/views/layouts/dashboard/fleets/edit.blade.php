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
        <h2>Edit Fleet <b>»</b> {{ $fleet->fleet_no }}</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('fleet', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <form class="col-md-11 m-auto" action="{{ url('/dashboard/fleet/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name="ft_id" value="{{ $fleet->id }}">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fleet_no" class="form-label">Fleet No <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="fleet_no" name="fleet_no" value="{{ $fleet->fleet_no }}" required>
            </div> 
            <div class="col-md-6">
                <label for="license_pl_no" class="form-label">License plate No</label>
                <input type="text" class="form-control" id="license_pl_no" name="license_pl_no" value="{{ $fleet->license_pl_no }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="vin_no" class="form-label">VIN No</label>
                <input type="text" class="form-control" id="vin_no" name="vin_no" value="{{ $fleet->vin_no }}">
            </div>
            <div class="col-md-6">
                <label for="gas_type" class="form-label">Gas Type</label>
                <select class="form-select" id="gas_type" name="gas_type">
                    <option value="Diesel" {{ $fleet->gas_type == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                    <option value="Gas" {{ $fleet->gas_type == 'Gas' ? 'selected' : '' }}>Gas</option>
                </select> 
            </div>
        </div>  
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="start_millage" class="form-label">Start Millage</label>
                <input type="text" class="form-control" id="start_millage" name="start_millage" value="{{ $fleet->start_millage }}">
            </div> 
            <div class="col-md-6">
                <label for="stop_millage" class="form-label">Stop Millage</label>
                <input type="text" class="form-control" id="stop_millage" name="stop_millage" value="{{ $fleet->stop_millage }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $fleet->date }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>
