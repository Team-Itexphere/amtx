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
    .table th, .table td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 2px 3px;
    }

    .table.sec th, .table.sec td {
        padding: 4px 3px;
    }

    .black-box  {
        width: 24px;
        height: 12px;
        border: 1px solid black;
    }
        
    .black-box.filled {
        background: black;
    }

    .no-bd td {
      border: none;
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>{{ isset($_GET['edit']) ? 'Edit' : 'Add' }} <b>»</b> Impressed Current CP Test</h2>
    </div>

    <form class="col-md-11 m-auto" id="test-form" action="{{ isset($_GET['edit']) ? url('/dashboard/tests/add/iccp-test?edit=') . $_GET['edit'] : url('/dashboard/tests/add/iccp-test') }}" method="POST" enctype="multipart/form-data">
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
            <h4 class="text-center mb-3">Other Details</h4>
            
            <!-- begin other data -->
            
            <table class="table" style="font-size: 11px; margin-top: -3px; line-height: 14px;">
                <thead>
                    <tr style="display: none;">
                        <th></th>               
                    </tr>
                </thead>
                <tr>
                    <td style="font-weight: 700; background: #d9d9d9; width: 48%"><center>REASON SURVEY WAS CONDUCTED (mark only one)</center></td>
                </tr>
                <tr>        
                    <td>
                        <select name="reason">
                            <option value="3yrs">Routine - 3 year</option>
                            <option value="6mnths">Routine - within 6 months of installation</option>
                            <option value="after-mod">Re-survey after repair/modification</option>
                        </select>
                        <br>Date next cathodic protection survey must be conducted by: <input type="date" name="conducted_date" />
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 700; background: #d9d9d9; width: 48%"><center>CATHODIC PROTECTION TESTER'S EVALUATION (mark only one)</center></td>
                </tr>
                <tr>        
                    <td style="padding: 0;">
                        <table class="no-bd">
                            <thead>
                                <tr style="display: none;">
                                    <th></th>
                                </tr>
                            </thead>
                            <tr>
                                <td class="align-middle">
                                    <select name="evaluation">
                                        <option value="PASS">PASS</option>
                                        <option value="FAIL">FAIL</option>
                                        <option value="INCONCLUSIVE">INCONCLUSIVE</option>
                                    </select>
                                </td>
                            </tr>
                        </table>          
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 700; background: #d9d9d9; width: 48%"><center>CRITERIA APPLICABLE TO EVALUATION (mark all that apply)</center></td>
                </tr>      
                <tr>
                    <td>
                        <select name="criteria_appli">
                            <option value="2">Structure-to-soil potential more negative than -850 mV with respect to a Cu/CuSO₄ reference electrode with protective current momentarily Interrupted ("instant-off").</option>
                            <option value="3">Structure tested exhibits at least 100 mV of cathodic polarization ("100 mV polarization).</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 700; background: #d9d9d9; width: 48%"><center>ACTION REQUIRED AS A RESULT OF THIS EVALUATION (mark only one)</center></td>
                </tr>
                <tr>
                    <td>
                        <select name="action_req">
                            <option value="NONE">NONE</option>
                            <option value="RETEST">RETEST</option>
                            <option value="REPAIR">REPAIR</option>
                        </select>
                    </td>
                </tr>
            </table>

            <table class="table" style="font-size: 11px; margin-top: -17px; line-height: 14px; text-align: center;">
                <thead>
                    <tr style="display: none;">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tr>
                    <td colspan="5" style="font-weight: 700; background: #d9d9d9;"><center>DESCRIPTION OF UST SYSTEM</center></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                </tr>
                <tr>
                    <td style="width: 10%;"><b>TANK #</b></td>
                    <td style="width: 18%;"><b>PRODUCT</b></td>
                    <td style="width: 18%;"><b>CAPACITY</b></td>
                    <td style="width: 18%;"><b>TANK MATERIAL</b></td>
                    <td style="width: 36%;"><b>PIPING MATERIAL</b></td>
                </tr>
                <tr class="tank-des-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                </tr>
                <tr class="tank-des-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                </tr>
                <tr class="tank-des-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                </tr>
                <tr class="tank-des-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                </tr>
                <tr class="tank-des-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                </tr>

            </table>

            <table class="table" style="font-size: 11px; margin-top: -17px; line-height: 14px; text-align: center;">
                <thead>
                    <tr style="display: none;">
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
                <tr>
                    <td colspan="8" style="font-weight: 700; background: #d9d9d9;"><center>IMPRESSED CURRENT RECTIFIER DATA</center></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                </tr>
                <tr style="font-size: 8px;">
                    <td colspan="2" style="text-align: left;"><b>Rectifier Manufacturer:</b></td>
                    <td style="display:none;"></td>
                    <td colspan="3"><input type="text" class="w-100" name="rec_man" /></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td colspan="3"><b>Rectifier Serial Number:</b><input type="text" class="w-100" name="rec_serial" /></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                </tr>
                <tr style="font-size: 8px;">
                    <td colspan="2" style="text-align: left;"><b>Rectifier Model:</b></td>
                    <td style="display:none;"></td>
                    <td colspan="2"><input type="text" class="w-100" name="rec_model" /></td>
                    <td style="display:none;"></td>
                    <td colspan="4"><b>Rated DC Output:</b> <input type="text" name="rec_volt" /><input type="text" name="rec_amp" /></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                </tr>
                <tr>
                    <td class="align-middle" rowspan="2"><b>EVENT</b></td>
                    <td class="align-middle" rowspan="2"><b>DATE</b></td>
                    <td colspan="2"><b>TAP SETTINGS</b></td>
                    <td style="display:none;"></td>
                    <td colspan="2"><b>DC OUTPUT</b></td>
                    <td style="display:none;"></td>
                    <td class="align-middle" rowspan="2"><b>HOUR METER</b></td>
                    <td class="align-middle" rowspan="2"><b>COMMENTS</b></td>
                </tr>
                <tr>
                    <td><b>COARSE</b></td>
                    <td><b>FINE</b></td>
                    <td><b>VOLTS</b></td>
                    <td><b>AMPS</b></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                </tr>
                <tr class="event-des-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                </tr>
                <tr class="event-des-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                </tr>
            </table>

            <table class="table" style="font-size: 11px; margin-top: -17px; line-height: 14px; text-align: center;">
                <thead>
                    <tr style="display: none;">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tr>
                    <td colspan="7" style="font-weight: 700; background: #d9d9d9; width: 48%"><center>IMPRESSED CURRENT CATHODIC PROTECTION SYSTEM SURVEY RESULTS</center></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                    <td style="display:none;"></td>
                </tr>
                <tr style="font-size: 8px;">
                    <td style="width: 10%;"><b>STRUCTURE</b></td>
                    <td style="width: 18%;"><b>CONTACT POINT</b></td>
                    <td style="width: 18%;"><b>REF CELL PLACEMENT</b></td>
                    <td style="width: 9%;"><b>ON VOLT</b></td>
                    <td style="width: 9%;"><b>OFF VOLT</b></td>
                    <td style="width: 20%;"><b>100 mV POLARIZATION</b></td>
                    <td><b>PASS/FAIL</b></td>
                </tr>
                <tr class="result-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td>
                        <select class="test-dt w-100">
                            <option value="">- select -</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </td>
                </tr>
                <tr class="result-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td>
                        <select class="test-dt w-100">
                            <option value="">- select -</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </td>
                </tr>
                <tr class="result-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td>
                        <select class="test-dt w-100">
                            <option value="">- select -</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </td>
                </tr>
                <tr class="result-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td>
                        <select class="test-dt w-100">
                            <option value="">- select -</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </td>
                </tr>
                <tr class="result-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td>
                        <select class="test-dt w-100">
                            <option value="">- select -</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </td>
                </tr>
                <tr class="result-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td>
                        <select class="test-dt w-100">
                            <option value="">- select -</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </td>
                </tr>
                <tr class="result-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td>
                        <select class="test-dt w-100">
                            <option value="">- select -</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </td>
                </tr>
                <tr class="result-item">
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td><input type="text" class="test-dt w-100" /></td>
                    <td>
                        <select class="test-dt w-100">
                            <option value="">- select -</option>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                        </select>
                    </td>
                </tr>
            </table>

            <!-- end other data -->

            <input type="hidden" id="tank-des-items" name="tank_des_items" value="">
            <input type="hidden" id="event-des-items" name="event_des_items" value="">
            <input type="hidden" id="result-items" name="result_items" value="">
        </div>

        <button type="button" id="frm_submit" class="btn btn-primary mt-2">{{ isset($_GET['edit']) ? 'Update' : 'Create' }}</button>
    </form>
</div>


<script>

    function collectData(selector, outputSelector) {
        let allItemsData = [];
        $(selector).each(function() {
            let rowData = [];
            $(this).find('.test-dt').each(function() {
                rowData.push($(this).val());
            });
            if (rowData.length > 0) {
                allItemsData.push(rowData);
            }
        });
        $(outputSelector).val(JSON.stringify(allItemsData));
    }

    $('#frm_submit').on('click', function(e) {

        event.preventDefault(e);

        var form = $('#test-form')[0];

        if (form.checkValidity() === false) {
            form.reportValidity();
            return;
        }
        
        collectData('.tank-des-item', '#tank-des-items');
        collectData('.event-des-item', '#event-des-items');
        collectData('.result-item', '#result-items');

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