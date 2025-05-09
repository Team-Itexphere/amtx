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

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>My Profile</h2>
        <!--button class="btn btn-primary" onclick="window.location.href='{{ route('employees', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button-->
    </div>
    <form class="col-md-11 m-auto" action="{{ url('/dashboard/my-profile') }}" method="POST">
        @csrf

        <input type="hidden" name="u_id" value="{{ $user->id }}">
        <div class="row mb-3" style="{{ $user->role == 6 ? 'display: none;' : '' }}">
            <div class="col-md-3">
                <label for="role" class="form-label">User Role <span class="text-danger">*</span></label>
                <select class="form-select" id="role" name="role" required>
                    <option value="" style="text-align:center;">-- Select Role --</option>
                    @auth
                        @if(Auth::user()->role == 1)
                          <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Super Admin</option>
                        @endif
                        @if(Auth::user()->role < 3)
                          <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Admin</option>
                        @endif
                        @if(Auth::user()->role < 4)
                          <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>Office Staff</option>
                        @endif
                        @if(Auth::user()->role < 5)
                          <option value="4" {{ $user->role == 4 ? 'selected' : '' }}>Field Tech Supervisor</option>
                        @endif
                    @endauth
                    <option value="5" {{ $user->role == 5 ? 'selected' : '' }}>Field Tech</option>
                    <option value="6" {{ $user->role == 6 ? 'selected' : '' }}>Customer</option>
                </select> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label"><span class="cus-lb">Store </span><span class="emp-lb">Employee </span>Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div> 
            <div class="col-md-6">
                <label for="phone" class="form-label"><span class="cus-lb">Store </span><span class="emp-lb">Employee </span>Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" required>
            </div> 
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label"><span class="cus-lb">Store </span><span class="emp-lb">Employee </span>Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            {{--<div id="fleet-wrap" class="col-md-6 pt-3">
                <label for="name" class="form-label">Fleet</label>
                <input type="text" class="form-control" id="fleet" name="fleet" value="{{ $user->fleet }}">
            </div>--}}
        </div>
        {{--<div class="row mb-3 customer-add" style="display:none;">
            <h6 class="text-center my-5">Additional Customer Details</h6>
            <div class="col-md-6">
                <label for="com_name" class="form-label">Corporation Name</label>
                <input type="text" class="form-control" id="com_name" name="com_name" value="{{ $user->com_name }}">
            </div> 
            <div class="col-md-6">
                <label for="own_name" class="form-label">Operator Name</label>
                <input type="text" class="form-control" id="own_name" name="own_name" value="{{ $user->own_name }}">
            </div> 
        </div>
        <div class="row mb-3 customer-add" style="display:none;">
            <div class="col-md-6">
                <label for="str_addr" class="form-label">Store Address</label>
                <input type="text" class="form-control" id="str_addr" name="str_addr" value="{{ $user->str_addr }}">
            </div> 
            <div class="col-md-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" value="{{ $user->city }}">
            </div>
            <div class="col-md-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" value="{{ $user->state }}">
            </div>
        </div>
        <div class="row mb-3 customer-add" style="display:none;">
            <div class="col-md-6">
                <label for="zip_code" class="form-label">Zip Code</label>
                <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ $user->zip_code }}">
            </div> 
            <div class="col-md-6">
                <label for="str_phone" class="form-label">Contact Person Phone Number</label>
                <input type="tel" class="form-control" id="str_phone" name="str_phone" value="{{ $user->str_phone }}">
            </div> 
        </div>
        <div class="row mb-3 customer-add" style="display:none;">
            <div class="col-md-6">
                <label for="cp_name" class="form-label">Contact Person Name</label>
                <input type="text" class="form-control" id="cp_name" name="cp_name" value="{{ $user->cp_name }}">
            </div>
            <div class="col-md-6">
                <label for="com_to_inv" class="form-label">Company to Invoice From</label>
                <select class="form-select" id="com_to_inv" name="com_to_inv">
                    <option value="AMTS" {{ $user->com_to_inv == 'AMTS' ? 'selected' : '' }}>AMTS</option>
                    <option value="Petro-Tank Solutions" {{ $user->com_to_inv == 'Petro-Tank Solutions' ? 'selected' : '' }}>Petro-Tank Solutions</option>
                </select> 
            </div>
        </div>
        <div class="row mb-3 customer-add" style="display:none;">
            <div class="col-md-6">
                <label for="own_email" class="form-label">Owner Email</label>
                <input type="email" class="form-control" id="own_email" name="own_email" value="{{ $user->own_email }}">
            </div>
            <div class="col-md-6">
                <label for="fac_id" class="form-label">Facility ID (Unique)</label>
                <input type="text" class="form-control" id="fac_id" name="fac_id" value="{{ $user->fac_id }}">
            </div>
        </div>--}}
        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>


<script>

$(document).ready(function() {
    function role_changed() {
        var roleVal = $('#role').val();

        if (roleVal < 4) {
            $('.customer-add *, #fleet-wrap *').prop('disabled', true);
            $('.customer-add, #fleet-wrap, .cus-lb').hide();
            $('.emp-lb').show();
        } else if (roleVal == 4 || roleVal == 5) {
            $('.customer-add *').prop('disabled', true);
            $('#fleet-wrap *').prop('disabled', false);
            $('.customer-add, .cus-lb').hide();
            $('#fleet-wrap, .emp-lb').show();
        } else {
            $('.customer-add *').prop('disabled', false);
            $('#fleet-wrap *').prop('disabled', true);
            $('.customer-add, #fleet-wrap *, .cus-lb').show();
            $('#fleet-wrap, .emp-lb').hide();
        }
    };

    role_changed();
    $('#role').change( role_changed );
});

</script>