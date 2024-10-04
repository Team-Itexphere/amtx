    <div class="row mb-4">
        <div class="col-md-3 pe-0">
            <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('em') ? $_GET['s'] : '' }}">
        </div>
        <div class="col-md-3 pe-0">
            <select class="form-select rolefilterSelect">
                <option value="" {{ !isset($_GET['role']) ? 'selected' : '' }}>Filter by User Role</option>
                <option value="1" {{ isset($_GET['role']) && $_GET['role'] == '1' ? 'selected' : '' }}>Super Admin</option>
                <option value="2" {{ isset($_GET['role']) && $_GET['role'] == '2' ? 'selected' : '' }}>Admin</option>
                <option value="3" {{ isset($_GET['role']) && $_GET['role'] == '3' ? 'selected' : '' }}>Office Staff</option>
                <option value="4" {{ isset($_GET['role']) && $_GET['role'] == '4' ? 'selected' : '' }}>Field Tech Supervisor</option>
                <option value="5" {{ isset($_GET['role']) && $_GET['role'] == '5' ? 'selected' : '' }}>Field Tech</option>
                <option value="6" {{ isset($_GET['role']) && $_GET['role'] == '6' ? 'selected' : '' }}>Customer</option>
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
                    <th>User Role</th>
                    <th>Full Name</th>
                    <th>Store</th>
                    <th>Phone No.</th>
                    <th>Pay</th>
                    <th>LPRG Date</th>
                    <th>Hire Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Docs</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $s = isset($_GET['s']) && request()->has('em') ? $_GET['s'] : null;
                    $fltRole = isset($_GET['role']) ? $_GET['role'] : null;
                    $users = paginateCollection( filterRecords(\App\Models\User::class, null, null, null, $s, $fltRole, null, 'em') );
                @endphp
                @foreach($users as $user)
                    @if($user->role > 2)
                        <tr>
                            <td class="align-middle">{{ ($user->role == 1 ? 'Super Admin' : ($user->role == 2 ? 'Admin' : ($user->role == 3 ? 'Office Staff' : ($user->role == 4 ? 'Field Tech Supervisor' : ($user->role == 5 ? 'Field Tech' : 'Customer'))))) }}</td>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">
                                @if(count($user->stores) > 0)
                                    @if($user->role > 2)
                                        <?php $storeNames = $user->stores->pluck('name')->implode(', '); ?>
                                        {{ $storeNames }}
                                    @else
                                        <span class="text-secondary">All</span>
                                    @endif
                                @else
                                    @if($user->role < 3)
                                        <span class="text-secondary">All</span>
                                    @else
                                        <span class="text-danger">No Store</span>
                                    @endif
                                @endif
                            </td> 
                            <td class="align-middle">{{ $user->phone }}</td>
                            <td class="align-middle text-end">{{ $user->pay }}</td>
                            
                            <td class="align-middle">{{ \Carbon\Carbon::parse($user->lpg_date)->format('m/d/Y') }}</td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($user->hire_date)->format('m/d/Y') }}</td>
                            <td class="text-center align-middle">
                                <span class="d-inline-block {{ empty($user->fire_date) ? 'bg-success text-white' : 'bg-danger text-white' }} rounded" style="padding: 5px; min-width: 55px;">
                                    {{ empty($user->fire_date) ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                @if($user->docs_path)
                                    <a href="{{ url('/employee-docs') }}/{{$user->docs_path}}" target="_blank" download>Download</a>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <button class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('employees', ['edit' => $user->id]) }}'"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $users->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>