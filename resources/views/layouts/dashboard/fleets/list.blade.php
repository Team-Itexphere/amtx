<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Fleet</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('fleet', ['add']) }}'"><i class="fa fa-plus"></i> Add New</button>
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
                    <th>Fleet No</th> 
                    <th>License Plate No</th>
                    <th>VIN No</th>
                    <th>Gas Type</th>
                    <th class="text-center">Fleet Routing</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fleets as $fleet)
                    <tr>
                        <td class="align-middle">{{ $fleet->fleet_no }}</td>
                        <td class="align-middle">{{ $fleet->license_pl_no }}</td>
                        <td class="align-middle">{{ $fleet->vin_no }}</td>
                        <td class="align-middle">{{ $fleet->gas_type }}</td>
                        <td class="align-middle text-center">
                            @if($fleet->fleet_routings)
                                <a href="{{ route('fleet-routing', ['view' => $fleet->id]) }}">View</a>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <button class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('fleet', ['edit' => $fleet->id]) }}'"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $fleets->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>
