<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Stores
            @if(request()->has('list') && request()->has('idle'))
                <b>»</b> Idle
            @elseif(request()->has('list') && request()->has('cons'))
                <b>»</b> Under Construction
            @elseif(request()->has('list') && request()->has('inactive'))
                <b>»</b> Inactive
            @endif
        </h2>
        @if(Auth::user()->role < 3)
        <button class="btn btn-primary" onclick="window.location.href='{{ route('stores', ['add']) }}'"><i class="fa fa-plus"></i> Add New</button>
        @endif
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
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
        <table class="table table-bordered" id="storeTable">
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Address</th>
                    <th>Recent work orders</th>
                    <th>Phone</th>
                    <th>Fax</th>
                    <th>Size</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stores as $store)
                    <tr>
                        <td class="align-middle">{{ $store->name }}</td>
                        <td class="align-middle">{{ $store->address }}</td>
                        <td class="align-middle text-center">
                            @if($store->Work_orders->count() > 0)
                                <a href="{{ route('work-orders', ['list' => $store->id, 'type' => 'store']) }}">Order History</a>
                            @endif
                            </td>
                        <td class="align-middle">{{ $store->phone }}</td>
                        <td class="align-middle">{{ $store->fax }}</td>
                        <td class="align-middle text-end">{{ $store->size }}</td>
                        <td class="text-center align-middle">
                            <button class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('stores', ['view' => $store->id]) }}'"><i class="fa fa-eye"></i></button>
                            @if(Auth::user()->role < 3)
                            <button class="btn btn-secondary p-0 px-1" onclick="window.location.href='{{ route('stores', ['edit' => $store->id]) }}'"><i class="fa fa-edit"></i></button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $stores->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>
