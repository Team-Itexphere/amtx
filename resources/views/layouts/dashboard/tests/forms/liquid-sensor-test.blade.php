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

    #items-table td {
        font-size: 15px;
        padding: 5px;
    }

    .test-item {
        box-shadow: 0 1px 0px #0000001f;
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>{{ isset($_GET['edit']) ? 'Edit' : 'Add' }} <b>»</b> Liquid Sensor Test</h2>
    </div>

    <form class="col-md-11 m-auto" id="test-form" action="{{ isset($_GET['edit']) ? url('/dashboard/tests/add/ls-test?edit=') . $_GET['edit'] : url('/dashboard/tests/add/ls-test') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Customer <span class="text-danger">*</span></label>
                <select class="form-select" id="customer_id" name="customer_id" required>
                    <option value="">- Select Customer -</option>
                    @foreach($customers_all as $customer)
                        <option value="{{ $customer->id }}" {{ $testing?->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->fac_id }})</option>
                    @endforeach
                </select> 
            </div>
            <div class="col-md-6">
                <label class="form-label">Technician <span class="text-danger">*</span></label>
                <select class="form-select" id="tech_id" name="tech_id" required>
                    <option value="">- Select Technician -</option>
                    @foreach($technicians_all as $technician)
                        <option value="{{ $technician->id }}" {{ $testing?->tech_id == $technician->id ? 'selected' : '' }}>{{ $technician->name }}</option>
                    @endforeach
                </select> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" value="{{ $testing?->date }}">
            </div>
        </div>

        <div class="mt-4 mb-3 pt-2 pb-0 px-4 rounded" style="border: 2px solid #53884047;">
            <h4 class="text-center mb-3">Items Tested</h4>
            <table id="items-table" style="width: 100%; font-size: 15px;">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-light-subtle test-item">
                        <td>Sensor Location</td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][0] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][1] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][2] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][3] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][4] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][5] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][6] : '' }}"></td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Product Stored</td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][0] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][1] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][2] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][3] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][4] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][5] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][6] : '' }}"></td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Type of Sensor</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[2][0] == 'Yes' ? 'selected' : '' }}>Discriminating</option>
                                <option value="No" {{ $testing && $test[2][0] == 'No' ? 'selected' : '' }}>Non-discriminating</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[2][1] == 'Yes' ? 'selected' : '' }}>Discriminating</option>
                                <option value="No" {{ $testing && $test[2][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[2][2] == 'Yes' ? 'selected' : '' }}>Discriminating</option>
                                <option value="No" {{ $testing && $test[2][2] == 'No' ? 'selected' : '' }}>Non-discriminating</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[2][3] == 'Yes' ? 'selected' : '' }}>Discriminating</option>
                                <option value="No" {{ $testing && $test[2][3] == 'No' ? 'selected' : '' }}>Non-discriminating</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[2][4] == 'Yes' ? 'selected' : '' }}>Discriminating</option>
                                <option value="No" {{ $testing && $test[2][4] == 'No' ? 'selected' : '' }}>Non-discriminating</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[2][5] == 'Yes' ? 'selected' : '' }}>Discriminating</option>
                                <option value="No" {{ $testing && $test[2][5] == 'No' ? 'selected' : '' }}>Non-discriminating</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[2][6] == 'Yes' ? 'selected' : '' }}>Discriminating</option>
                                <option value="No" {{ $testing && $test[2][6] == 'No' ? 'selected' : '' }}>Non-discriminating</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Test Liquid</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[3][0] == 'Yes' ? 'selected' : '' }}>Water</option>
                                <option value="No" {{ $testing && $test[3][0] == 'No' ? 'selected' : '' }}>Product</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[3][1] == 'Yes' ? 'selected' : '' }}>Water</option>
                                <option value="No" {{ $testing && $test[3][1] == 'No' ? 'selected' : '' }}>Product</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[3][2] == 'Yes' ? 'selected' : '' }}>Water</option>
                                <option value="No" {{ $testing && $test[3][2] == 'No' ? 'selected' : '' }}>Product</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[3][3] == 'Yes' ? 'selected' : '' }}>Water</option>
                                <option value="No" {{ $testing && $test[3][3] == 'No' ? 'selected' : '' }}>Product</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[3][4] == 'Yes' ? 'selected' : '' }}>Water</option>
                                <option value="No" {{ $testing && $test[3][4] == 'No' ? 'selected' : '' }}>Product</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[3][5] == 'Yes' ? 'selected' : '' }}>Water</option>
                                <option value="No" {{ $testing && $test[3][5] == 'No' ? 'selected' : '' }}>Product</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[3][6] == 'Yes' ? 'selected' : '' }}>Water</option>
                                <option value="No" {{ $testing && $test[3][6] == 'No' ? 'selected' : '' }}>Product</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Is the ATG console clear of any active or recurring warnings or alarms regarding the leak sensor? If the sensor is in alarm and functioning, indicate why</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[4][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[4][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[4][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[4][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[4][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[4][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[4][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[4][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[4][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[4][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[4][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[4][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[4][6] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[4][6] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Is the sensor alarm circuit operational?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][6] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][6] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Has sensor been inspected and in good operating condition?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[6][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[6][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[6][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[6][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[6][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[6][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[6][6] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][6] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>When placed in the test liquid, does the sensor trigger an alarm?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[7][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[7][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[7][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[7][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[7][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[7][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[7][6] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][6] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>When an alarm is triggered, is the sensor properly identified on the ATG console?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[8][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[8][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[8][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[8][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[8][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[8][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[8][6] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][6] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>

                    <tr class="border border-light-subtle test-item">
                        <td>Test Results</td>
                        <td>
                            <select class="form-select test-dt">
                            <option value="">- select -</option>
                            <option value="Pass" {{ $testing && $test[9][0] == 'Pass' ? 'selected' : '' }}>Pass</option>
                            <option value="Fail" {{ $testing && $test[9][0] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                            <option value="">- select -</option>
                            <option value="Pass" {{ $testing && $test[9][1] == 'Pass' ? 'selected' : '' }}>Pass</option>
                            <option value="Fail" {{ $testing && $test[9][1] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                            <option value="">- select -</option>
                            <option value="Pass" {{ $testing && $test[9][2] == 'Pass' ? 'selected' : '' }}>Pass</option>
                            <option value="Fail" {{ $testing && $test[9][2] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                            <option value="">- select -</option>
                            <option value="Pass" {{ $testing && $test[9][3] == 'Pass' ? 'selected' : '' }}>Pass</option>
                            <option value="Fail" {{ $testing && $test[9][3] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                            <option value="">- select -</option>
                            <option value="Pass" {{ $testing && $test[9][4] == 'Pass' ? 'selected' : '' }}>Pass</option>
                            <option value="Fail" {{ $testing && $test[9][4] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                            <option value="">- select -</option>
                            <option value="Pass" {{ $testing && $test[9][5] == 'Pass' ? 'selected' : '' }}>Pass</option>
                            <option value="Fail" {{ $testing && $test[9][5] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                            <option value="">- select -</option>
                            <option value="Pass" {{ $testing && $test[9][6] == 'Pass' ? 'selected' : '' }}>Pass</option>
                            <option value="Fail" {{ $testing && $test[9][6] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <input type="hidden" id="items" name="items" value="">
        </div>

        <button type="button" id="frm_submit" class="btn btn-primary mt-2">{{ isset($_GET['edit']) ? 'Update' : 'Create' }}</button>
    </form>
</div>


<script>

    $('#frm_submit').on('click', function(e) {

        event.preventDefault(e);

        var form = $('#test-form')[0];

        if (form.checkValidity() === false) {
            form.reportValidity();
            return;
        }
        
        let allRowsData = [];

        $('.test-item').each(function() {
            let rowData = [];

            $(this).find('.test-dt').each(function() {
                rowData.push($(this).val());
            });

            if (rowData.length > 0) {
                allRowsData.push(rowData);
            }
        });

        $('#items').val( JSON.stringify(allRowsData) );
        $('#test-form').submit();
    });

    var customer_id = document.querySelector('#customer_id');

    dselect(customer_id, {
        search: true
    });

    var tech_id = document.querySelector('#tech_id');

    dselect(tech_id, {
        search: true
    });

</script>