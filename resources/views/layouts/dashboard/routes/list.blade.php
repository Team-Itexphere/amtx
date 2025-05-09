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
        <h2>Route List</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('routes', ['add']) }}'"><i class="fa fa-plus"></i> Add New</button>
    </div>
    <div class="row mb-4">
        <div class="col-md-2">
            <select class="form-select companyfilterSelect">
                <option value="" {{ !isset($_GET['company']) ? 'selected' : '' }}>Filter by Company</option>
                <option value="AMTS" {{ isset($_GET['company']) && $_GET['company'] == 'AMTS' ? 'selected' : '' }}>AMTS</option>
                <option value="Petro-Tank Solutions" {{ isset($_GET['company']) && $_GET['company'] == 'Petro-Tank Solutions' ? 'selected' : '' }}>PTS</option>
            </select>
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
                    <th>Route #</th>
                    <th>Route Name</th>
                    <th class="text-center"># of Locations</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($routes as $route)
                    <tr>
                        <td class="align-middle"><a href="{{ route('routes', ['edit' => $route->id]) }}">{{ $route->num }}</a></td>
                        <td class="align-middle">{{ $route->name }}</td>
                        <td class="align-middle text-center">
                            @if($route->ro_locations->count() > 0)
                                <a href="{{ route('routes', ['view' => $route->id]) }}">{{ $route->ro_locations->count() }}</a>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <button type="button" class="delete-item btn btn-danger p-0 px-1" data-action="route/{{ $route->id }}"><i class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $routes->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.filterButton').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            var company = $('.companyfilterSelect').length > 0 ? $('.companyfilterSelect').val() : '';
    
            if(company){
                params.set('company', company);
            } else {
                params.delete('company');
            }
            
            params.delete('page');
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    
        $('.filter-reset').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            params.delete('company');
            params.delete('page');
            
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    });
</script>
