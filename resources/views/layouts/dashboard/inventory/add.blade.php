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
        <h2>Add New Inventory</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('inventory', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <form class="col-md-11 m-auto" action="{{ url('/dashboard/inventory/add') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="part_no" class="form-label">Part No <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="part_no" name="part_no" required>
            </div> 
            <div class="col-md-6">
                <label for="manufacturer" class="form-label">Manufacturer</label>
                <input type="text" class="form-control" id="manufacturer" name="manufacturer">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div> 
            <div class="col-md-6">
                <label for="qty" class="form-label">QTY</label>
                <input type="number" class="form-control" id="qty" name="qty">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="purchase_price" class="form-label">Purchase Price</label>
                <input type="text" class="form-control" id="purchase_price" name="purchase_price">
            </div>
            <div class="col-md-6">
                <label for="selling_price" class="form-label">Selling Price</label>
                <input type="text" class="form-control" id="selling_price" name="selling_price">
            </div>
        </div>  
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="warranty" class="form-label">Warranty</label>
                <input type="text" class="form-control" id="warranty" name="warranty">
            </div> 
            <div class="col-md-6">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="Pump">Pump</option>
                    <option value="Tank">Tank</option>
                </select> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="serial" class="form-label">Serial</label>
                <input type="text" class="form-control" id="serial" name="serial">
            </div>
            <div class="col-md-6">
                <label for="fleet_id" class="form-label">Location</label>
                <select class="form-select" id="fleet_id" name="fleet_id">
                    <option value="0">Warehouse</option>
                    @foreach($fleets as $fleet)
                        <option value="{{ $fleet->id }}">{{ $fleet->fleet_no }}</option>
                    @endforeach
                </select> 
            </div>
        </div> 
        <button type="submit" class="btn btn-primary mt-2">Create</button>
    </form>
</div>


<script>

    var fleet_id = document.querySelector('#fleet_id');

    dselect(fleet_id, {
        search: true
    });

</script>