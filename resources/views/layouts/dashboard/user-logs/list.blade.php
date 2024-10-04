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
        <h2>User Activities</h2>
  </div>
    <div class="row mb-4">
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
                    <th>User</th>
                    <th>Logged In At</th> 
                    <th>Logged Out At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user_logs as $user_log)
                    <tr>
                        <td class="align-middle">{{ ($user_log->user->role == 1 ? 'Super Admin' : ($user_log->user->role == 2 ? 'Admin' : ($user_log->user->role == 3 ? 'Office Staff' : ($user_log->user->role == 4 ? 'Field Tech Supervisor' : ($user_log->user->role == 5 ? 'Field Tech' : 'Customer'))))) }}</td>
                        <td class="align-middle">{{ $user_log->user->name }}</td>
                        <td class="align-middle">{{ $user_log->login_at ? \Carbon\Carbon::parse($user_log->login_at)->format('m/d/Y - h:i A') : 'N/A' }}</td>
                        <td class="align-middle">{{ $user_log->logout_at ? \Carbon\Carbon::parse($user_log->logout_at)->format('m/d/Y - h:i A') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $user_logs->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>