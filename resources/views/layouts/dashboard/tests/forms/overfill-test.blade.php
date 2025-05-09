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
        <h2>{{ isset($_GET['edit']) ? 'Edit' : 'Add' }} <b>»</b> Overfill Test</h2>
    </div>

    <form class="col-md-11 m-auto" id="test-form" action="{{ isset($_GET['edit']) ? url('/dashboard/tests/add/overfill-test?edit=') . $_GET['edit'] : url('/dashboard/tests/add/overfill-test') }}" method="POST" enctype="multipart/form-data">
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
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-light-subtle test-item">
                        <td>Product Grade</td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][0] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][1] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][2] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][3] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][4] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[0][5] : '' }}"></td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Tank Number</td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][0] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][1] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][2] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][3] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][4] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[1][5] : '' }}"></td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Tank Volume, gallons</td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[2][0] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[2][1] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[2][2] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[2][3] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[2][4] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[2][5] : '' }}"></td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Tank Diameter, inches</td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[3][0] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[3][1] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[3][2] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[3][3] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[3][4] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[3][5] : '' }}"></td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Overfill Prevention Device Brand</td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[4][0] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[4][1] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[4][2] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[4][3] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[4][4] : '' }}"></td>
                        <td><input type="text" class="form-control test-dt" value="{{ $testing ? $test[4][5] : '' }}"></td>
                    </tr>

                    <tr class="border border-light-subtle test-item">
                        <td>Type</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][0] == 'Yes' ? 'selected' : '' }}>Automatic Shutoff Device</option>
                                <option value="No" {{ $testing && $test[5][0] == 'No' ? 'selected' : '' }}>Ball Float Valve</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][1] == 'Yes' ? 'selected' : '' }}>Automatic Shutoff Device</option>
                                <option value="No" {{ $testing && $test[5][1] == 'No' ? 'selected' : '' }}>Ball Float Valve</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][2] == 'Yes' ? 'selected' : '' }}>Automatic Shutoff Device</option>
                                <option value="No" {{ $testing && $test[5][2] == 'No' ? 'selected' : '' }}>Ball Float Valve</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][3] == 'Yes' ? 'selected' : '' }}>Automatic Shutoff Device</option>
                                <option value="No" {{ $testing && $test[5][3] == 'No' ? 'selected' : '' }}>Ball Float Valve</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][4] == 'Yes' ? 'selected' : '' }}>Automatic Shutoff Device</option>
                                <option value="No" {{ $testing && $test[5][4] == 'No' ? 'selected' : '' }}>Ball Float Valve</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[5][5] == 'Yes' ? 'selected' : '' }}>Automatic Shutoff Device</option>
                                <option value="No" {{ $testing && $test[5][5] == 'No' ? 'selected' : '' }}>Ball Float Valve</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>1.Drop tube removed from tank?</td>
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
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>2.Drop tube and float mechanisms free of debris?</td>
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
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>3.Float moves freely without binding and poppet moves into flow path?</td>
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
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>4.Bypass valve in the drop tube open and free of blockage (if present)?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[9][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[9][0] == 'No' ? 'selected' : '' }}>No</option>
                                <option value="No" {{ $testing && $test[9][0] == 'NP' ? 'selected' : '' }}>Not Present</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[9][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[9][1] == 'No' ? 'selected' : '' }}>No</option>
                                <option value="No" {{ $testing && $test[9][1] == 'NP' ? 'selected' : '' }}>Not Present</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[9][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[9][2] == 'No' ? 'selected' : '' }}>No</option>
                                <option value="No" {{ $testing && $test[9][2] == 'NP' ? 'selected' : '' }}>Not Present</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[9][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[9][3] == 'No' ? 'selected' : '' }}>No</option>
                                <option value="No" {{ $testing && $test[9][3] == 'NP' ? 'selected' : '' }}>Not Present</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[9][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[9][4] == 'No' ? 'selected' : '' }}>No</option>
                                <option value="No" {{ $testing && $test[9][4] == 'NP' ? 'selected' : '' }}>Not Present</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[9][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[9][5] == 'No' ? 'selected' : '' }}>No</option>
                                <option value="No" {{ $testing && $test[9][5] == 'NP' ? 'selected' : '' }}>Not Present</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>5.Flapper adjusted to shut off flow at 95% capacity?*</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[10][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[10][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[10][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[10][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[10][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[10][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>1.Tank top fittings vapor- tight and leak-free?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[11][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[11][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[11][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[11][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[11][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[11][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[11][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[11][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[11][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[11][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[11][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[11][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>2.Ball float cage free of debris?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[12][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[12][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[12][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[12][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[12][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[12][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>3.Ball free of holes and cracks and moves freely in cage?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[13][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[13][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[13][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[13][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[13][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[13][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[13][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[13][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[13][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[13][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[13][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[13][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>4.Vent hole in pipe open and near top of tank?</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[14][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[14][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[14][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[14][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[14][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[14][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[14][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[14][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[14][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[14][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[14][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[14][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>5.Ball float pipe proper length to restrict flow at 90% capacity?***</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[15][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[15][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[15][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[15][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[15][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[15][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[15][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[15][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[15][4] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[15][4] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Yes" {{ $testing && $test[15][5] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[15][5] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>

                    <tr class="border border-light-subtle test-item">
                        <td>Test Results</td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Pass" {{ $testing && $test[16][0] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[16][0] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Pass" {{ $testing && $test[16][1] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[16][1] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Pass" {{ $testing && $test[16][2] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[16][2] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Pass" {{ $testing && $test[16][3] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[16][3] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Pass" {{ $testing && $test[16][4] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[16][4] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select test-dt">
                                <option value="">- select -</option>
                                <option value="Pass" {{ $testing && $test[16][5] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[16][5] == 'Fail' ? 'selected' : '' }}>Fail</option>
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