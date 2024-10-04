<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Fleet Routing <b>»</b> {{ $fleet->fleet_no }}</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('fleet', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To Fleets</button>
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
                    <th>Date</th> 
                    <th>Start Millage</th>
                    <th>Stop Millage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fleet_rs as $fleet_r)
                    <tr>
                        <td class="align-middle">{{ $fleet_r->date ? \Carbon\Carbon::parse($fleet_r->date)->format('m/d/Y') : '' }}</td>
                        <td class="align-middle">{{ $fleet_r->start_millage }}</td>
                        <td class="align-middle">{{ $fleet_r->stop_millage }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $fleet_rs->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>
