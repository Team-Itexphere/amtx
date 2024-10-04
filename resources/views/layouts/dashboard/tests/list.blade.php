<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Testings List</h2>
    </div>
    <div class="row mb-4">
        <div class="col-md-2">
            <select class="form-select typefilterSelect">
                <option value="" {{ !isset($_GET['type-filter']) ? 'selected' : '' }}>Filter by type</option>
                <option value="release-detection-annual-testing" {{ isset($_GET['type-filter']) && $_GET['type-filter'] == 'release-detection-annual-testing' ? 'selected' : '' }}>Release Detection Annual Testing</option>
                <option value="atg-test" {{ isset($_GET['type-filter']) && $_GET['type-filter'] == 'atg-test' ? 'selected' : '' }}>ATG Test</option>
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
                    @if(!isset($_GET['type-filter']))
                        <th>Testing Type</th>
                    @endif
                    <th>Date</th>
                    <th>Customer</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testings as $testing)
                    <tr>
                        @if(!isset($_GET['type-filter']))
                            <td class="align-middle">{{ $testing->type }}</td>
                        @endif
                        <td class="align-middle">{{ $testing->date }}</td>
                        <td class="align-middle">{{ $testing->customer->name }}</td>
                        <td class="align-middle text-center">
                            <button type="button" class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ url('/') }}/{{ $testing->pdf_path }}'"><i class="fa fa-eye"></i></button>
                            <button type="button" class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('tests', ['type' => $testing->type, 'edit' => $testing->id]) }}'"><i class="fa fa-edit"></i></button>
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
    
            var type = $('.typefilterSelect').val();
            
            if(type){
                params.set('type-filter', type);
            } else {
                params.delete('type-filter');
            }
            
            params.delete('page');
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    
        $('.filter-reset').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            params.delete('type-filter');
            params.delete('page');
            
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    });
</script>