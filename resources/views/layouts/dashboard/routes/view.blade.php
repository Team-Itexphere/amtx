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
        <h2><a href="{{ route('routes', ['list']) }}">Route</a> <b>»</b> #{{ $route->num }} <b>»</b> Locations</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('routes') }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
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
                    <th>Store Name</th>
                    <th>Facility ID</th>
                    <th class="text-center">Monthly Inspection Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locations as $location)
                    <tr>
                        <td class="align-middle">{{ $location['cus_name'] }}</td>
                        <td class="align-middle"><a href="{{ route('employees', ['list', 'cus', 's' => $location['cus_fac_id']]) }}">{{ $location['cus_fac_id'] }}</a></td>
                        <td class="align-middle text-end">{{ $location['amount'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="align-middle"><strong>Total Stores : {{ $loc_count }}</strong></td>
                    <td></td>
                    <td class="align-middle text-end"><strong>Total $ Amount : {{ $loc_rate_total }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
