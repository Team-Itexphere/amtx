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
        <h2>Maintenance Logs <b>»</b> {{ $customer->fac_id }}</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ url()->previous() }}'"><i class="fa fa-arrow-left"></i> Back</button>
  </div>
    <div class="row mb-4">
        <!--div class="col-md-2">
            <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
        </div-->

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
                    <th>Date of Repair</th>
                    <th>Part Description</th> 
                    <th>Part Location</th> 
                    <th>Problem of Description</th>
                    <th>Contractor's Name</th> 
                    <th>Technician</th>
                    <th class="text-center">Images</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintain_logs as $maintain_log)
                    <tr>
                        <td class="align-middle">{{ $maintain_log->date }}</td>
                        <td class="align-middle">{{ $maintain_log->descript }}</td>
                        <td class="align-middle">{{ $maintain_log->location }}</td>
                        <td class="align-middle">{{ $maintain_log->des_problem }}</td>
                        <td class="align-middle">{{ $maintain_log->customer->com_to_inv }}</td>
                        <td class="align-middle">{{ $maintain_log->technician->name }}</td>
                        <td class="align-middle text-center"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $maintain_logs->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>