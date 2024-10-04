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
        <h2>Inspections
            @if(isset($_GET['line']))
                <b>»</b> Under Annual Line & Leak
            @elseif(isset($_GET['stage']))
                <b>»</b> Under Stage 1
            @elseif(isset($_GET['cal']))
                <b>»</b> Under Calibration
            @endif
        </h2>
    </div>
    <div class="row mb-4">
        <div class="col-md-2">
            <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary filterButton">Filter</button>
            <button class="btn btn-primary filter-reset">Reset</button>
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
                    <th>Inspection Type</th>
                    <th>Customer</th>
                    <th>Technician</th>
                    <th>Test Type</th>
                    <th class="text-center">Inspection Doc.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testings as $testing)
                    <tr>
                        <td class="align-middle">{{  $testing->created_at->format('m/d/Y H:i:s') }}</td>
                        <td class="align-middle">{{ $testing->ro_location->route->insp_type }}</td>
                        <td class="align-middle">{{ $testing->customer ? $testing->customer->name : '' }} ({{ $testing->customer->fac_id }})</td>
                        <td class="align-middle">{{ $testing->technician ? $testing->technician->name : '' }}</td>
                        <td class="align-middle">{{ $testing->type }}</td>
                        <td class="align-middle text-center">
                            <button class="btn btn-primary p-0 px-1" onclick="window.open('{{ route('testing', ['pdf' => $testing->id]) }}', '_blank')"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('testing', ['pdf' => $testing->id, 'download']) }}'"><i class="fa fa-download"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $testings->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.filterButton').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            var s = $('.searchInput').val();
    
            if(s != ''){
                params.set('s', s);
            } else {
                params.delete('s');
            }
            
            params.delete('page');
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    
        $('.filter-reset').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            params.delete('s');
            params.delete('page');
            
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    });
</script>
