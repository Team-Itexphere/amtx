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

@if(!isset($_GET['parent']) && $user->role == 6)
    <style>
        .customer-add {
            display: none !important;
        }
    </style>
@elseif(isset($_GET['parent']))
    <style>
        .u-role-type {
            display: none !important;
        }
    </style>
@endif

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    #add-btn-cont {
        width: 50%;
        text-align: center;
        margin: 30px auto;
    }

    .btn-divider {
        width: 100%;
        height: 0;
    }

    #add_item {
        margin-top: -30px;
    }

    .inv-item {
        box-shadow: 0 2px 5px #0000001f;
    }
    
    span.select2-selection.select2-selection--multiple.form-control {
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice, .select2-container .select2-search--inline .select2-search__field {
        margin-top: -1px;
        height: 26px;
    }
    
    .delete-ajax {
        position: relative;
        margin-bottom: -15px;
        float: right;
        right: -5px;
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Edit {{ isset($_GET['parent']) ? 'Store' : 'User' }} <b>»</b> {{ $user->name }}</h2>
        <div>
            @if(!$user->customers()->exists() && !isset($_GET['parent']) && $user->role == 6)
                <button class="btn btn-primary" onclick="window.location.href='{{ route('employees', ['add', 'parent' => $user->id]) }}'"><i class="fa fa-plus"></i> Add Store</button>
            @endif
            <button class="btn btn-primary" onclick="window.location.href='{{ url()->previous() }}'"><i class="fa fa-arrow-left"></i> Back</button>
        </div>
    </div>
    <form id="user-form" class="col-md-11 m-auto" action="{{ url('/dashboard/employees/edit') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        
        <input type="hidden" name="u_id" value="{{ $user->id }}">
        <div class="row mb-3">
            <div class="col-md-3 u-role-type">
                <label for="user-type" class="form-label">User Type</label>
                @if($user->role == 6)
                    <select class="form-select" style="background: transparent; margin-bottom: -38px; position: relative;" disabled></select>
                @endif
                <select class="form-select" id="user-type">
                    <option value="cus">Customer</option>
                    @if($user->role !== 6)
                        <option value="emp" selected>Employee</option>
                    @endif
                </select> 
            </div>
            <div class="col-md-3 emp-types" style="{{ $user->role == 6 ? 'display: none;' : '' }}">
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
                    <option value="6" {{ $user->role == 6 ? 'selected' : '' }} style="display: none;">Customer</option>
                </select> 
            </div>
            <div class="col-md-3 customer-add">
                <label for="cus-type" class="form-label">User Type</label>
                <select class="form-select" id="cus-type" name="cus_type">
                    <option value="Monthly" {{ $user->cus_type == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="Annual" {{ $user->cus_type == 'Annual' ? 'selected' : '' }}>Annual</option>
                </select> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label"><span class="cus-lb">Store</span> Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" readonly required>
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label"><span class="cus-lb">Store</span> Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
            </div> 
        </div>
        <div class="row mb-3">
            <div class="col-md-6 log-f">
                <label for="email" class="form-label"><span class="cus-lb">Store</span> Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly required>
            </div>
            <div class="col-md-6 log-f">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" readonly>
            </div>
            <div id="fleet-wrap" class="col-md-6 pt-3">
                <label for="fleet_id" class="form-label">Fleet</label>
                <select class="form-select" id="fleet_id" name="fleet_id">
                    @foreach($fleets as $fleet)
                        <option value="{{ $fleet->id }}" {{ $user->fleet_id == $fleet->id ? 'selected' : '' }}>{{ $fleet->fleet_no }}</option>
                    @endforeach
                </select> 
            </div>
        </div>
        <div class="row mb-3 customer-add" style="display:none;">
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
                <label for="fac_id" class="form-label">Facility ID (Unique) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="fac_id" name="fac_id" value="{{ $user->fac_id }}" required>
            </div>
        </div>
        <div class="row mb-3 customer-add" style="display:none;">
            <div class="col-md-6">
                <label for="own_email" class="form-label">Owner Email</label>
                <input type="email" class="form-control" id="own_email" name="own_email" value="{{ $user->own_email }}">
            </div>
            <div class="col-md-6">
                <label for="com_to_inv" class="form-label">Company to Invoice From</label>
                <select class="form-select" id="com_to_inv" name="com_to_inv">
                    <option value="AMTS" {{ $user->com_to_inv == 'AMTS' ? 'selected' : '' }}>AMTS</option>
                    <option value="Petro-Tank Solutions" {{ $user->com_to_inv == 'Petro-Tank Solutions' ? 'selected' : '' }}>Petro-Tank Solutions</option>
                </select> 
            </div>
        </div>
        
        <div class="row mb-3 customer-add">
            <h6 class="text-center my-5">Customer's Site Info</h6>
            <div class="col-md-6">
                <label for="fu_brand" class="form-label">Fuel Brand</label>
                <input type="text" class="form-control" id="fu_brand" name="fu_brand" value="{{ $site_info?->fu_brand }}">
            </div>
            <div class="col-md-6">
                <label for="truck_stop" class="form-label">Truck stop</label>
                <div class="d-flex">
                    <div class="form-check me-4 mt-1">
                        <input class="form-check-input" type="radio" name="truck_stop" id="truck_yes" value="Yes" {{ $site_info?->truck_stop == 'Yes' ? 'checked' : '' }}>
                        <label class="form-check-label" for="truck_yes">Yes</label>
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="radio" name="truck_stop" id="truck_no" value="No" {{ $site_info?->truck_stop == 'No' ? 'checked' : '' }}>
                        <label class="form-check-label" for="truck_no">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3 customer-add">
            <div class="col-md-6">
                <label for="dis_brand" class="form-label">Dispenser Brand</label>
                <input type="text" class="form-control" id="dis_brand" name="dis_brand" value="{{ $site_info?->dis_brand }}">
            </div> 
            <div class="col-md-6">
                <label for="dis_model" class="form-label">Dispenser Model</label>
                <input type="text" class="form-control" id="dis_model" name="dis_model" value="{{ $site_info?->dis_model }}">
            </div> 
        </div>
        <div class="row mb-3 customer-add">
            <div class="col-md-6">
                <label for="dis_sumps" class="form-label">Does it have sumps under dispenser?</label>
                <div class="d-flex">
                    <div class="form-check me-4 mt-1">
                        <input class="form-check-input dis_sumps" type="radio" name="dis_sumps" value="Yes" {{ $site_info?->dis_sumps == 'Yes' ? 'checked' : '' }}>
                        <label class="form-check-label" for="sumps_yes">Yes</label>
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input dis_sumps" type="radio" name="dis_sumps" value="No" {{ $site_info?->dis_sumps == 'No' ? 'checked' : '' }}>
                        <label class="form-check-label" for="sumps_no">No</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 dis_type_wrap" style="{{ $site_info?->dis_sumps != 'Yes' ? 'display: none;' : '' }}">
                <label for="dis_type" class="form-label">Sumps Type</label>
                <select class="form-select" id="dis_type" name="dis_type">
                    <option value="Poly" {{ $site_info?->dis_type == 'Poly' ? 'selected' : '' }}>Poly</option>
                    <option value="Fiberglass" {{ $site_info?->dis_type == 'Fiberglass' ? 'selected' : '' }}>Fiberglass</option>
                </select> 
            </div>
            <div class="col-md-3">
                <label class="form-label">Number of Vents</label>
                <input type="number" class="form-control" name="vents_count" value="{{ $site_info?->vents_count }}">
            </div>
        </div>
        <div class="row mb-3 customer-add">
            <div class="col-md-3">
                <label for="h_many_3_0" class="form-label">Number of 3+0 Dispensers</label>
                <input type="number" class="form-control" id="h_many_3_0" name="h_many_3_0" value="{{ $site_info?->h_many_3_0 }}">
            </div> 
            <div class="col-md-3">
                <label for="h_many_3_1" class="form-label">Number of 3+1 Dispensers</label>
                <input type="number" class="form-control" id="h_many_3_1" name="h_many_3_1" value="{{ $site_info?->h_many_3_1 }}">
            </div> 
            <div class="col-md-3">
                <label for="h_many_h_flows" class="form-label">Number of High Flows Dispensers</label>
                <input type="number" class="form-control" id="h_many_h_flows" name="h_many_h_flows" value="{{ $site_info?->h_many_h_flows }}">
            </div> 
            <div class="col-md-3">
                <label for="tanks_count" class="form-label">Number of Tanks</label>
                <input type="number" class="form-control" id="tanks_count" name="tanks_count" value="{{ $site_info?->tanks_count }}">
            </div> 
        </div>
        <div class="row mb-3 customer-add">
            <div class="col-md-6">
                <label for="atg_brand" class="form-label">ATG Brand</label>
                <input type="text" class="form-control" id="atg_brand" name="atg_brand" value="{{ $site_info?->atg_brand }}">
            </div>
            <div class="col-md-6">
                <label for="atg_sensors" class="form-label">ATG Sensors</label>
                @php
                    $sens = [];
                    if($site_info?->atg_sensors){
                        $sens = json_decode($site_info->atg_sensors, true) ?? [];
                    }
                @endphp
                <select class="form-select" id="atg_sensors" name="atg_sensors[]" multiple>
                    <option value="NONE" {{ in_array('NONE', $sens) ? 'selected' : '' }}>NONE</option>
                    <option value="Sensor" {{ in_array('Sensor', $sens) ? 'selected' : '' }}>Sensor</option>
                    <option value="CSLD" {{ in_array('CSLD', $sens) ? 'selected' : '' }}>CSLD</option>
                    <option value="PLLD" {{ in_array('PLLD', $sens) ? 'selected' : '' }}>PLLD</option>
                </select>
            </div>
        </div>
        <div class="row mb-3 customer-add">
            <div class="col-md-6">
                <label for="relay_brand" class="form-label">Relay Brand</label>
                <input type="text" class="form-control" id="relay_brand" name="relay_brand" value="{{ $site_info?->relay_brand }}">
            </div>
            <div class="col-md-6"> 
                <label for="pos_system" class="form-label">POS System</label>
                <input type="text" class="form-control" id="pos_system" name="pos_system" value="{{ $site_info?->pos_system }}">
            </div>
        </div>
        
        <div class="mt-5 mb-3 pt-2 pb-0 px-4 rounded customer-add" style="border: 2px solid #53884047;">
            <h4 class="text-center mb-3">Tanks</h4>
            <div id="item-cont">
                @php
                    $tank_count = 1;
                @endphp
                @if($site_info)
                    @foreach($site_info?->site_info_tanks as $tank)
                        <div class="row mb-3 p-2 rounded-1 border border-light-subtle tank">
                            <div class="col-md-11">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Tank Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control tank_name" value="{{ $tank->tank_name }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tank ID</label>
                                        <select class="form-select fu_type">
                                            <option value="Regular" {{ $tank->fu_type == 'Regular' ? 'selected' : '' }}>Regular</option>
                                            <option value="Super" {{ $tank->fu_type == 'Super' ? 'selected' : '' }}>Super</option>
                                            <option value="Plus" {{ $tank->fu_type == 'Plus' ? 'selected' : '' }}>Plus</option>
                                            <option value="Auto Diesel" {{ $tank->fu_type == 'Auto Diesel' ? 'selected' : '' }}>Auto Diesel</option>
                                            <option value="Truck Diesel" {{ $tank->fu_type == 'Truck Diesel' ? 'selected' : '' }}>Truck Diesel</option>
                                            <option value="DEF" {{ $tank->fu_type == 'DEF' ? 'selected' : '' }}>DEF</option>
                                        </select> 
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Size</label>
                                        <input type="text" class="form-control size" value="{{ $tank->size }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Diameter</label>
                                        <input type="number" class="form-control diameter" value="{{ $tank->diameter }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Material</label>
                                        <input type="text" class="form-control material" value="{{ $tank->material }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Spill Bucket brand</label>
                                        <input type="text" class="form-control sb_brand" value="{{ $tank->sb_brand }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Wall</label>
                                        <div class="d-flex">
                                            <div class="form-check me-4 mt-1">
                                                <input class="form-check-input wall_type" type="radio" name="wall_type_{{ $tank_count }}" value="Single" {{ $tank->wall_type == 'Single' ? 'checked' : '' }}>
                                                <label class="form-check-label">Single</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input class="form-check-input wall_type" type="radio" name="wall_type_{{ $tank_count }}" value="Double" {{ $tank->wall_type == 'Double' ? 'checked' : '' }}>
                                                <label class="form-check-label">Double</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Drain</label>
                                        <div class="d-flex">
                                            <div class="form-check me-4 mt-1">
                                                <input class="form-check-input drain" type="radio" name="drain_{{ $tank_count }}" value="Yes" {{ $tank->drain == 'Yes' ? 'checked' : '' }}>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input class="form-check-input drain" type="radio" name="drain_{{ $tank_count }}" value="No" {{ $tank->drain == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Number of Gallon Buckets</label>
                                        <input type="number" class="form-control h_many_g_bucket" value="{{ $tank->h_many_g_bucket }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Inside depth (inches)</label>
                                        <input type="text" class="form-control in_denpth" value="{{ $tank->in_denpth }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Overfill Prevention</label>
                                        <select class="form-select overfill_prev">
                                            <option value="Ball Floats" {{ $tank->overfill_prev == 'Ball Floats' ? 'selected' : '' }}>Ball Floats</option>
                                            <option value="Flappers" {{ $tank->overfill_prev == 'Flappers' ? 'selected' : '' }}>Flappers</option>
                                        </select> 
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Vent Type</label>
                                        <select class="form-select vent_type">
                                            <option value="Opw623v" {{ $tank->vent_type == 'Opw623v' ? 'selected' : '' }}>Opw623v</option>
                                            <option value="Emco" {{ $tank->vent_type == 'Emco' ? 'selected' : '' }}>Emco</option>
                                            <option value="Franklin Fuel" {{ $tank->vent_type == 'Franklin Fuel' ? 'selected' : '' }}>Franklin Fuel</option>
                                            <option value="Opw523v" {{ $tank->vent_type == 'Opw523v' ? 'selected' : '' }}>Opw523v</option>
                                        </select> 
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">STP manufacturer</label>
                                        <input type="text" class="form-control stp_manf" value="{{ $tank->stp_manf }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Leak detector</label>
                                        <input type="text" class="form-control leak_detector" value="{{ $tank->leak_detector }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Does it have STP sumps?</label>
                                        <div class="d-flex">
                                            <div class="form-check me-4 mt-1">
                                                <input class="form-check-input stp_sumps" type="radio" name="stp_sumps_{{ $tank_count }}" value="Yes" {{ $tank->stp_sumps == 'Yes' ? 'checked' : '' }}>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input class="form-check-input stp_sumps" type="radio" name="stp_sumps_{{ $tank_count }}" value="No" {{ $tank->stp_sumps == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 stps_type_wrap" style="{{ $tank->stp_sumps != 'Yes' ? 'display: none;' : '' }}">
                                        <label class="form-label">Sump Type</label>
                                        <div class="d-flex">
                                            <div class="form-check me-4 mt-1">
                                                <input class="form-check-input stps_type" type="radio" name="stps_type_{{ $tank_count }}" value="Poly" {{ $tank->stps_type == 'Poly' ? 'checked' : '' }}>
                                                <label class="form-check-label">Poly</label>
                                            </div>
                                            <div class="form-check mt-1">
                                                <input class="form-check-input stps_type" type="radio" name="stps_type_{{ $tank_count }}" value="Fiberglass" {{ $tank->stps_type == 'Fiberglass' ? 'checked' : '' }}>
                                                <label class="form-check-label">Fiberglass</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 text-end pt-2">
                                <button type="button" class="btn-close close-item m-auto" aria-label="Close"></button>
                            </div>
                        </div>
                        @php
                            $tank_count++;
                        @endphp
                    @endforeach
                @endif
            </div>

            <div class="d-flex justify-content-center" id="add-btn-cont">
                <div class="border border-2 border-success rounded-pill btn-divider">
                    <button type="button" id="add_item" class="btn btn-primary py-1 px-2"  aria-label="Add"><i class="fa fa-plus"></i></button>
                </div>
            </div>

            <input type="hidden" id="tanks" name="tanks" value="">
        </div>
        
        <div class="row mb-3 px-3 customer-add">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="info_lock" name="info_lock" value="1" {{ $site_info?->lock ? 'checked' : '' }}>
                <label class="form-check-label" for="login">Allow Edit Info</label>
            </div>
        </div>
        
        <div class="row mb-3 px-3" id="work-for">
            <div class="col-md-3" style="padding-left: 0 !important">
                <label class="form-label" for="select-work-for">Work For</label>
                <select class="form-select" id="select-work-for" name="work_for">
                    <option value="AMTX" {{ $user->work_for == 'AMTX' ? 'selected' : '' }}>AMTX</option>
                    <option value="PTS" {{ $user->work_for == 'PTS' ? 'selected' : '' }}>PTS</option>
                    <option value="Both" {{ $user->work_for == 'Both' ? 'selected' : '' }}>Both</option>
                </select> 
            </div>
        </div>
        <div class="row mb-3 px-3">
            <div class="form-check col-md-2">
                <input class="form-check-input" type="checkbox" id="login" name="login" value="1" {{ $user->login ? 'checked' : '' }}>
                <label class="form-check-label" for="login">Allow Login</label>
            </div>
        </div>
        <div class="row mb-3 px-3">
            <div class="form-check col-md-3 customer-add">
                <input class="form-check-input" type="checkbox" id="rec_logs" name="rec_logs" value="1" {{ $user->rec_logs ? 'checked' : '' }}>
                <label class="form-check-label" for="login">Enable Rectifier Logs</label>
            </div>
        </div>
        <button type="button" id="frm_submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>


@if(!isset($_GET['parent']) && $user->role == 6)

@php
$parent_id = $user->id;
//if(!$user->customers()->exists()){
    $users[] = $user;
//}
@endphp

<style>
.detach {
    padding: 0 2px;
}

.detach img {
    margin-top: -4px;
}
</style>
<div class="container mt-5 col-md-11 mx-auto">
    <div class="table-responsive">
        <table class="table table-bordered" id="userTable">
            <thead>
                <tr>
                    <th>Facility ID</th>
                    <th>Store Name</th>
                    <th>Corporation Name</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Store Address</th>
                    <th>Contact Person Name</th>
                    <th>Licenses</th>
                    <th>Site info</th>
                    <th>Compliance Documents</th>
                    <th>Maintenance Logs</th>
                    <th>S.I.R./Inventory Control</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @if($user->role >= Auth::user()->role || Auth::user()->role == 6)
                        <tr>
                            <td class="align-middle"><a href="{{ route('employees', ['edit' => $user->id, 'parent']) }}">{{ $user->fac_id }}</a></td>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->com_name }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->phone }}</td>
                            <td class="align-middle">{{ $user->str_addr }}</td>
                            <td class="align-middle">{{ $user->cp_name }}</td>                        
                            <td class="align-middle text-center">
                                <a href="{{ route('cus-licenses', ['id' => $user->id]) }}">View</a>
                            </td> 
                            <td class="align-middle text-center">
                                <a href="#" class="site-info-btn" data-bs-toggle="modal" data-bs-target="#site_infoModal" data-id="{{ $user->id }}">View</a>
                            </td> 
                            <td class="align-middle text-center">
                                <a href="{{ route('comp-docs', ['id' => $user->id]) }}">View</a>
                            </td>
                            <td class="align-middle text-center">
                                <a href="{{ route('maintain-logs', ['id' => $user->id]) }}">View</a>
                            </td>
                            <td class="align-middle text-center">
                                <a href="{{ route('cus-sir-inv-docs', ['id' => $user->id]) }}">View</a>
                            </td>
                            <td class="align-middle text-center text-nowrap">
                                @if(Auth::user()->role == 1)
                                    <!--button class="delete-item btn btn-danger p-0 px-1" data-action="user/{{ $user->id }}"><i class="fa fa-trash-alt" title="Delete user"></i></button-->
                                    <button class="btn btn-secondary p-0 px-1" onclick="window.location.href='{{ route('switch_to') }}/{{ $user->id }}'" title="Switch as {{ $user->name }}"><i class="fa fa-sign-in"></i></button>
                                    @if($user->id != $parent_id)
                                        <button class="btn btn-danger detach" data-action="store?id={{ $user->id }}&parent={{ $parent_id }}"><img src="{{ asset('img') }}/unassign.png" width="20" /></button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!--popup-->
<div class="modal fade" id="site_infoModal" tabindex="-1" aria-labelledby="site_infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="site_infoModalLabel">Site Info <b>»</b> <img src="/img/loader.gif" width="20" class="info-loader" style="display: none;"><span id="info-cus-name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <style>
            .bd-bot {
                border-bottom: 1px solid #f1f1f1;
            }
            
            .ac-hd {
                border-bottom: 1px solid #ec6d2b;
                padding: 0 3px 2px 3px;
            }
            
            .ac-hd a {
                text-decoration: none;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            
            .ac-hd a[aria-expanded='true'] b {
                rotate: 180deg;
            }
        </style>
        <table style="border: none; width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td class="bd-bot">Fuel Brand</td>
                <td class="bd-bot" id="inf_fu_brand"></td>
            </tr>
            <tr>
                <td class="bd-bot">Truck stop</td>
                <td class="bd-bot" id="inf_truck_stop"></td>
            </tr>
            <tr>
                <td class="bd-bot">Dispenser Brand</td>
                <td class="bd-bot" id="inf_dis_brand"></td>
            </tr>
            <tr>
                <td class="bd-bot">Dispenser Model</td>
                <td class="bd-bot" id="inf_dis_model"></td>
            </tr>
            <tr>
                <td class="bd-bot">Does it have sumps under dispenser?</td>
                <td class="bd-bot" id="inf_dis_sumps"></td>
            </tr>
            <tr>
                <td class="bd-bot">Sumps Type</td>
                <td class="bd-bot" id="inf_dis_type"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of Vents</td>
                <td class="bd-bot" id="inf_vents_count"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of 3+0 Dispensers</td>
                <td class="bd-bot" id="inf_h_many_3_0"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of 3+1 Dispensers</td>
                <td class="bd-bot" id="inf_h_many_3_1"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of High Flows Dispensers</td>
                <td class="bd-bot" id="inf_h_many_h_flows"></td>
            </tr>
            <tr>
                <td class="bd-bot">Number of Tanks</td>
                <td class="bd-bot" id="inf_tanks_count"></td>
            </tr>
            <tr>
                <td class="bd-bot">ATG Brand</td>
                <td class="bd-bot" id="inf_atg_brand"></td>
            </tr>
            <tr>
                <td class="bd-bot">Relay Brand</td>
                <td class="bd-bot" id="inf_relay_brand"></td>
            </tr>
            <tr>
                <td class="bd-bot">POS System</td>
                <td class="bd-bot" id="inf_pos_system"></td>
            </tr>
            </tbody>
        </table>
        <div id="tanks-list-wrap">
            <h5 class="text-center mt-3">Tanks</h5>
            <div id="tanks-list"></div>
        </div>
        <div id="error-message" class="text-danger"></div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $('.site-info-btn').click(function() {
            var cusId = $(this).data('id');
            
            $('#info-cus-name').text('');
            $('#inf_fu_brand').text('');
            $('#inf_truck_stop').text('');
            $('#inf_dis_brand').text('');
            $('#inf_dis_model').text('');
            $('#inf_dis_sumps').text('');
            $('#inf_dis_type').text('');
            $('#inf_vents_count').text('');
            $('#inf_h_many_3_0').text('');
            $('#inf_h_many_3_1').text('');
            $('#inf_h_many_h_flows').text('');
            $('#inf_tanks_count').text('');
            $('#inf_atg_brand').text('');
            $('#inf_relay_brand').text('');
            $('#inf_pos_system').text('');
            $('#inf_tanks-list').html('');
            $('#inf_tanks-list-wrap').hide();
            
            $('.info-loader').show();
            
            $.ajax({
                url: '/dashboard/site-info',
                type: 'GET',
                data: { id: cusId },
                success: function(response) {
                    $('.info-loader').hide();
                    $('#info-cus-name').text(response.name ?? 'N/A');
                    $('#inf_fu_brand').text(response.fu_brand ?? 'N/A');
                    $('#inf_truck_stop').text(response.truck_stop ?? 'N/A');
                    $('#inf_dis_brand').text(response.dis_brand ?? 'N/A');
                    $('#inf_dis_model').text(response.dis_model ?? 'N/A');
                    $('#inf_dis_sumps').text(response.dis_sumps ?? 'N/A');
                    $('#inf_dis_type').text(response.dis_type ?? 'N/A');
                    $('#inf_vents_count').text(response.vents_count ?? 'N/A');
                    $('#inf_h_many_3_0').text(response.h_many_3_0 ?? 'N/A');
                    $('#inf_h_many_3_1').text(response.h_many_3_1 ?? 'N/A');
                    $('#inf_h_many_h_flows').text(response.h_many_h_flows ?? 'N/A');
                    $('#inf_tanks_count').text(response.tanks_count ?? 'N/A');
                    $('#inf_atg_brand').text(response.atg_brand ?? 'N/A');
                    $('#inf_relay_brand').text(response.relay_brand ?? 'N/A');
                    $('#inf_pos_system').text(response.pos_system ?? 'N/A');
                    
                    if(response.site_info_tanks){
                        var tanks = response.site_info_tanks;
                        var tanksHtml = '';

                        tanks.forEach(function(tank) {
                            tanksHtml += `
                                <div class="accordion-item">
                                    <h6 class="ac-hd accordion-header">
                                        <a href="#" data-bs-toggle="collapse" data-bs-target="#tank-${tank.id}">
                                            ${tank.tank_name ?? 'Untitled'} <b><i class="fa fa-angle-down"></i></b>
                                        </a>
                                    </h6>
                                    <div id="tank-${tank.id}" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <table style="border: none; margin: auto; width: 98%;">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="bd-bot">Tank ID</td>
                                                    <td class="bd-bot">${tank.fu_type ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Size</td>
                                                    <td class="bd-bot">${tank.size ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Diameter</td>
                                                    <td class="bd-bot">${tank.diameter ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Material</td>
                                                    <td class="bd-bot">${tank.material ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Spill Bucket brand</td>
                                                    <td class="bd-bot">${tank.sb_brand ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Wall</td>
                                                    <td class="bd-bot">${tank.wall_type ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Drain</td>
                                                    <td class="bd-bot">${tank.drain ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Number of Gallon Buckets</td>
                                                    <td class="bd-bot">${tank.h_many_g_bucket ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Inside depth (inches)</td>
                                                    <td class="bd-bot">${tank.in_denpth ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Overfill Prevention</td>
                                                    <td class="bd-bot">${tank.overfill_prev ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Vent Type</td>
                                                    <td class="bd-bot">${tank.vent_type ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">STP manufacturer</td>
                                                    <td class="bd-bot">${tank.stp_manf ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Leak detector</td>
                                                    <td class="bd-bot">${tank.leak_detector ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Does it have STP sumps?</td>
                                                    <td class="bd-bot">${tank.stp_sumps ?? 'N/A'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="bd-bot">Sump Type</td>
                                                    <td class="bd-bot">${tank.stps_type ?? 'N/A'}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    
                        $('#tanks-list').html(tanksHtml);
                        $('#tanks-list-wrap').show();
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred when getting site info: " + error);
                    $('.info-loader').hide();
                }
            });
        });
    });
</script>

@endif


@if(isset($_GET['parent']) && $user->role == 6)

@php
    $pictures = $user->pictures()->get()->groupBy('type');
@endphp

{{--@if($pictures->isNotEmpty())--}}
    <div class="container mt-5 col-md-11 mx-auto">
        <div class="mt-3 d-flex flex-wrap gap-4">
            {{-- Loop through each type and create a card if the type exists --}}
            @foreach (array_filter(['picture', 'atgs', 'atgi', $user->rec_logs ? 'rec_log' : null]) as $type)
                {{--@if($pictures->has($type))--}}
                    <div class="card mb-4" style="width: 13rem; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modal-{{ $type }}">
                        @if($type == 'picture')
                            <span class="text-center" style="font-size: 114px;">📷</span>
                        @elseif($type == 'atgs')
                            <span class="text-center" style="font-size: 114px;">📊</span>
                        @elseif($type == 'atgi')
                            <span class="text-center" style="font-size: 114px;">📦</span>
                        @elseif($type == 'rec_log')
                            <span class="text-center" style="font-size: 114px;">📝</span>
                        @endif
                        <div class="card-body text-center">
                            <a href="#" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#modal-{{ $type }}">
                                {{ $type == 'picture' ? 'Pictures' : ($type == 'atgs' ? 'Sensor/CSLD Tickets' : ($type == 'atgi' ? 'Inventory Tickets' : 'Rectifier Log')) }}
                            </a>
                        </div>
                    </div>

                    {{-- Modal for the type --}}
                    <div class="modal fade" id="modal-{{ $type }}" tabindex="-1" aria-labelledby="modalLabel-{{ $type }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel-{{ $type }}">{{ $type == 'picture' ? 'Pictures' : ($type == 'atgs' ? 'Sensor/CSLD Tickets' : 'Inventory Tickets') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        @if($pictures->has($type))
                                            @foreach ($pictures[$type] as $picture)
                                                <div class="col-4 mb-3 item">
                                                    <button class="delete-ajax btn btn-danger p-0 px-1" data-action="pic/{{ $picture->id }}"><i class="fa fa-trash-alt" title="Delete Image"></i></button>
                                                    <img src="{{ url('') . $picture->image }}" alt="{{ $type }}" class="img-fluid rounded">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{--@endif--}}
            @endforeach
        </div>
    </div>
{{--@endif--}}

@endif


<script>

$(document).ready(function() {
    
    var rd_only = true;
    $('#name, #email, #password, #fac_id').click(function() {
        if (rd_only) {
            $('#name, #email, #password, #fac_id').removeAttr('readonly');
            rd_only = false;
        }
    });
    
    function role_by_type(){
        if($('#user-type').val() == 'cus'){
            $('.emp-types, #work-for').hide();
            $('#role').val(6);
            $('.cus-lb').text('Store');
        } else {
            var first_opt = $('#role option:nth-child(2)').val();
            $('#role').val(first_opt);
            $('.emp-types, #work-for').show();
            $('.cus-lb').text('Employee');
        }
        role_changed();
    };
    role_by_type();    
    $('#user-type').on('change', function(){
        role_by_type();
    });

    function allow_login(){
        if($('#login').is(':checked')){
            $('.log-f input').prop('required', true);
            $('.log-f .text-danger').show();
        } else {
            $('.log-f input').prop('required', false);
            $('.log-f .text-danger').hide();
        }
    };
    allow_login();
    $('#login').on('change', function(){
        allow_login();
    });
    
    function role_changed() {
        var roleVal = $('#role').val();

        if (roleVal < 4) {
            $('.customer-add *, #fleet-wrap *').prop('disabled', true);
            $('.customer-add, #fleet-wrap').hide();
            $('.cus-lb').text('Employee');
        } else if (roleVal == 4 || roleVal == 5) {
            $('.customer-add *').prop('disabled', true);
            $('#fleet-wrap *').prop('disabled', false);
            $('.customer-add').hide();
            $('.cus-lb').text('Employee');
            $('#fleet-wrap').show();
        } else {
            $('.customer-add *').prop('disabled', false);
            $('#fleet-wrap *').prop('disabled', true);
            $('.customer-add, #fleet-wrap').show();
            $('.cus-lb').text('Store');
            $('#fleet-wrap').hide();
        }
    };

    role_changed();
    $('#role').change( role_changed );


    var fleet_id = document.querySelector('#fleet_id');

    dselect(fleet_id, {
        search: true
    });
    
    
    $('#atg_sensors').select2({
        tags: true,
        tokenSeparators: [',', ' '],
    }).next('.select2-container').find('.select2-selection').addClass('form-control');
    
    
    
    $('#add_item').on('click', function() {
        var tank_count = $('.tank').length + 1;
        
        var new_item = $(`<div class="row mb-3 p-2 rounded-1 border border-light-subtle tank">
                    <div class="col-md-11">
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">Tank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control tank_name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tank ID</label>
                                <select class="form-select fu_type">
                                    <option value="Regular">Regular</option>
                                    <option value="Super">Super</option>
                                    <option value="Plus">Plus</option>
                                    <option value="Auto Diesel">Auto Diesel</option>
                                    <option value="Truck Diesel">Truck Diesel</option>
                                    <option value="DEF">DEF</option>
                                </select> 
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Size</label>
                                <input type="text" class="form-control size">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Diameter</label>
                                <input type="number" class="form-control diameter">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Material</label>
                                <input type="text" class="form-control material">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Spill Bucket brand</label>
                                <input type="text" class="form-control sb_brand">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">Wall</label>
                                <div class="d-flex">
                                    <div class="form-check me-4 mt-1">
                                        <input class="form-check-input wall_type" type="radio" name="wall_type_${tank_count}" value="Single" checked>
                                        <label class="form-check-label">Single</label>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input wall_type" type="radio" name="wall_type_${tank_count}" value="Double">
                                        <label class="form-check-label">Double</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Drain</label>
                                <div class="d-flex">
                                    <div class="form-check me-4 mt-1">
                                        <input class="form-check-input drain" type="radio" name="drain_${tank_count}" value="Yes" checked>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input drain" type="radio" name="drain_${tank_count}" value="No">
                                        <label class="form-check-label">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Number of Gallon Buckets</label>
                                <input type="number" class="form-control h_many_g_bucket">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Inside depth (inches)</label>
                                <input type="text" class="form-control in_denpth">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">Overfill Prevention</label>
                                <select class="form-select overfill_prev">
                                    <option value="Ball Floats">Ball Floats</option>
                                    <option value="Flappers">Flappers</option>
                                </select> 
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Vent Type</label>
                                <select class="form-select vent_type">
                                    <option value="Opw623v">Opw623v</option>
                                    <option value="Emco">Emco</option>
                                    <option value="Franklin Fuel">Franklin Fuel</option>
                                    <option value="Opw523v">Opw523v</option>
                                </select> 
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">STP manufacturer</label>
                                <input type="text" class="form-control stp_manf">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Leak detector</label>
                                <input type="text" class="form-control leak_detector">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">Does it have STP sumps?</label>
                                <div class="d-flex">
                                    <div class="form-check me-4 mt-1">
                                        <input class="form-check-input stp_sumps" type="radio" name="stp_sumps_${tank_count}" value="Yes" checked>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input stp_sumps" type="radio" name="stp_sumps_${tank_count}" value="No">
                                        <label class="form-check-label">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 stps_type_wrap">
                                <label class="form-label">Sump Type</label>
                                <div class="d-flex">
                                    <div class="form-check me-4 mt-1">
                                        <input class="form-check-input stps_type" type="radio" name="stps_type_${tank_count}" value="Poly" checked>
                                        <label class="form-check-label">Poly</label>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input stps_type" type="radio" name="stps_type_${tank_count}" value="Fiberglass">
                                        <label class="form-check-label">Fiberglass</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 text-end pt-2">
                        <button type="button" class="btn-close close-item m-auto" aria-label="Close"></button>
                    </div>
                </div>`);

        new_item.appendTo('#item-cont');
        $('.tank .close-item').prop('disabled', false);
    });

    $(document).on('click', '.close-item', function() {
        //if($('body .close-item').length !== 1){
            this.closest('.tank').remove();
        //}
    });
    
    $(document).on('click', '.dis_sumps', function() {
        if($(this).val() == 'Yes'){
            $('.dis_type_wrap').show();
            $('#dis_type').prop('disabled', false);
        } else {
            $('.dis_type_wrap').hide();
            $('#dis_type').prop('disabled', true);
        }
    });
    
    $(document).on('click', '.stp_sumps', function() {
        if($(this).val() == 'Yes'){
            $(this).closest('.tank').find('.stps_type_wrap').show();
            $(this).closest('.tank').find('.stps_type').prop('disabled', false);
        } else {
            $(this).closest('.tank').find('.stps_type_wrap').hide();
            $(this).closest('.tank').find('.stps_type').prop('disabled', true);
        }
    });
    
    $('#frm_submit').on('click', function(e) {

        event.preventDefault(e);

        var form = $('#user-form')[0];

        if (form.checkValidity() === false) {
            form.reportValidity();
            return;
        }
        
        var tanks = [];

        $('.tank').each(function(index) {
            var $tank = $(this);

            var dataSet = {
                tank_name: $tank.find('.tank_name').val() ?? null,
                fu_type: $tank.find('.fu_type').val() ?? null,
                size: $tank.find('.size').val() ?? null,
                diameter: $tank.find('.diameter').val() ?? null,
                material: $tank.find('.material').val() ?? null,
                sb_brand: $tank.find('.sb_brand').val() ?? null,
                wall_type: $tank.find('.wall_type:checked').val() ?? null,
                drain: $tank.find('.drain:checked').val() ?? null,
                h_many_g_bucket: $tank.find('.h_many_g_bucket').val() ?? null,
                in_denpth: $tank.find('.in_denpth').val() ?? null,
                overfill_prev: $tank.find('.overfill_prev').val() ?? null,
                vent_type: $tank.find('.vent_type').val() ?? null,
                stp_manf: $tank.find('.stp_manf').val() ?? null,
                leak_detector: $tank.find('.leak_detector').val() ?? null,
                stp_sumps: $tank.find('.stp_sumps:checked').val() ?? null,
                stps_type: $tank.find('.stp_sumps:checked').val() == 'Yes' ? $tank.find('.stps_type:checked').val() : null,
            };

            tanks.push(dataSet);
        });

        $('#tanks').val( JSON.stringify(tanks) );
        $('#user-form').submit();
        
    });

});

</script>