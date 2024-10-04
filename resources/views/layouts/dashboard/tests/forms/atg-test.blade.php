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
        <h2>{{ isset($_GET['edit']) ? 'Edit' : 'Add' }} <b>»</b> ATG Test</h2>
    </div>

    <form class="col-md-11 m-auto" id="test-form" action="{{ isset($_GET['edit']) ? url('/dashboard/tests/add/atg-test?edit=') . $_GET['edit'] : url('/dashboard/tests/add/atg-test') }}" method="POST" enctype="multipart/form-data">
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
            <h4 class="text-center mb-3">Tanks Tested</h4>
            <table id="items-table" style="width: 100%; font-size: 15px;">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if($testing)
                        @php
                            $test = json_decode($testing->tanks, true);
                        @endphp
                    @endif
                    <tr class="border border-light-subtle test-item">
                        <td>Tank Number</td>
                        <td>
                            <input type="text" class="form-control tank_num_1 test-dt" value="{{ $testing ? $test[0][0] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control tank_num_2 test-dt" value="{{ $testing ? $test[0][1] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control tank_num_3 test-dt" value="{{ $testing ? $test[0][2] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control tank_num_4 test-dt" value="{{ $testing ? $test[0][3] : '' }}">
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>Product Stored</td>
                        <td>
                            <input type="text" class="form-control prod_stored_1 test-dt" value="{{ $testing ? $test[1][0] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control prod_stored_2 test-dt" value="{{ $testing ? $test[1][1] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control prod_stored_3 test-dt" value="{{ $testing ? $test[1][2] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control prod_stored_4 test-dt" value="{{ $testing ? $test[1][3] : '' }}">
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>ATG Brand and Model</td>
                        <td>
                            <input type="text" class="form-control brand_1 test-dt" value="{{ $testing ? $test[2][0] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control brand_2 test-dt" value="{{ $testing ? $test[2][1] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control brand_3 test-dt" value="{{ $testing ? $test[2][2] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control brand_4 test-dt" value="{{ $testing ? $test[2][3] : '' }}">
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>1. Tank Volume, gallons</td>
                        <td>
                            <input type="text" class="form-control data1_1 test-dt" value="{{ $testing ? $test[3][0] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data1_2 test-dt" value="{{ $testing ? $test[3][1] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data1_3 test-dt" value="{{ $testing ? $test[3][2] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data1_4 test-dt" value="{{ $testing ? $test[3][3] : '' }}">
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>2. Tank Diameter, inches</td>
                        <td>
                            <input type="text" class="form-control data2_1 test-dt" value="{{ $testing ? $test[4][0] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data2_2 test-dt" value="{{ $testing ? $test[4][1] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data2_3 test-dt" value="{{ $testing ? $test[4][2] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data2_4 test-dt" value="{{ $testing ? $test[4][3] : '' }}">
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>3. After removing the ATG from the tank, it has been inspected and any damaged or missing parts replaced?</td>
                        <td>
                            <select class="form-select data3_1 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[5][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select> 
                        </td>
                        <td>
                            <select class="form-select data3_2 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[5][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data3_3 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[5][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data3_4 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[5][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[5][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>4. Float moves freely on the stem without binding?</td>
                        <td>
                            <select class="form-select data4_1 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[6][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data4_2 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[6][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data4_3 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[6][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data4_4 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[6][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[6][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>5. Fuel float level agrees with the value programmed into the console?</td>
                        <td>
                            <select class="form-select data5_1 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[7][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data5_2 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[7][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data5_3 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[7][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data5_4 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[7][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[7][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>6. Water float level agrees with the value programmed into the console?</td>
                        <td>
                            <select class="form-select data6_1 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[8][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data6_2 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[8][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data6_3 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[8][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data6_4 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[8][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[8][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>7. Inch level from bottom of stem when 90% alarm is triggered.</td>
                        <td>
                            <input type="text" class="form-control data7_1 test-dt" value="{{ $testing ? $test[9][0] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data7_2 test-dt" value="{{ $testing ? $test[9][1] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data7_3 test-dt" value="{{ $testing ? $test[9][2] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data7_4 test-dt" value="{{ $testing ? $test[9][3] : '' }}">
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>8. Inch level at which the overfill alarm activates corresponds with value programmed in the gauge?</td>
                        <td>
                            <select class="form-select data8_1 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[10][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data8_2 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[10][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data8_3 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[10][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data8_4 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[10][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[10][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>9. Inch level from the bottom when the water float first triggers an alarm</td>
                        <td>
                            <input type="text" class="form-control data9_1 test-dt" value="{{ $testing ? $test[11][0] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data9_2 test-dt" value="{{ $testing ? $test[11][1] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data9_3 test-dt" value="{{ $testing ? $test[11][2] : '' }}">
                        </td>
                        <td>
                            <input type="text" class="form-control data9_4 test-dt" value="{{ $testing ? $test[11][3] : '' }}">
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td>10. Inch level at which the water float alarm activates corresponds with value programmed in the gauge?</td>
                        <td>
                            <select class="form-select data10_1 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[12][0] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][0] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data10_2 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[12][1] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][1] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data10_3 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[12][2] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][2] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select data10_4 test-dt">
                                <option value="">N/A
                                <option value="Yes" {{ $testing && $test[12][3] == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $testing && $test[12][3] == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="border border-light-subtle test-item">
                        <td><b>Test Results</b></td>
                        <td>
                            <select class="form-select res_1 test-dt">
                                <option value="">N/A
                                <option value="Pass" {{ $testing && $test[12][0] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[12][0] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select res_2 test-dt">
                                <option value="">N/A
                                <option value="Pass" {{ $testing && $test[12][1] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[12][1] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select res_3 test-dt">
                                <option value="">N/A
                                <option value="Pass" {{ $testing && $test[12][2] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[12][2] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select res_4 test-dt">
                                <option value="">N/A
                                <option value="Pass" {{ $testing && $test[12][3] == 'Pass' ? 'selected' : '' }}>Pass</option>
                                <option value="Fail" {{ $testing && $test[12][3] == 'Fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </td>
                    </tr>
                <tbody>
            </table>

            <input type="hidden" id="tests" name="tanks" value="">
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

        $('#tests').val( JSON.stringify(allRowsData) );
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