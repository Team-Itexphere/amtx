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
        <h2>{{ isset($_GET['edit']) ? 'Edit' : 'Add' }} <b>»</b> Stage 1 Test</h2>
    </div>

    <form class="col-md-11 m-auto" id="test-form" action="{{ isset($_GET['edit']) ? url('/dashboard/tests/add/stage-1-test?edit=') . $_GET['edit'] : url('/dashboard/tests/add/stage-1-test') }}" method="POST" enctype="multipart/form-data">
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
            
            <div style="font-size: 12px; margin: 5px 0 20px 0;"><u><b>Vapor Recovery System Installed:</b></u></div>

            <div style="border: 1px solid black; padding: 2px; margin-bottom: 20px;">
                <table class="table" style="font-size: 11px; margin-top: 0px; margin-bottom: 2px; line-height: 14px; text-align: center;">
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
                        <td class="align-middle" style="width: 10%; padding: 3px;"><b>System</b></td>
                        <td class="align-middle" style="width: 20%; padding: 3px;"><b>UST or AST</b></td>
                        <td class="align-middle" style="width: 20%; padding: 3px;"><b>Type of System¹</b></td>
                        <td class="align-middle" style="width: 20%; padding: 3px;"><b>Executive Order or Certification Number</b></td>
                        <td class="align-middle" style="width: 30%; padding: 3px;"><b>Test Purpose²</b></td>
                    </tr>
                </table>
                <table class="table" style="font-size: 11px; margin-top: 0px; margin-bottom: 0; line-height: 14px; text-align: center;">
                    <thead>
                        <tr style="display: none;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tr class="vapor-item">
                        <td class="align-middle" style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle" style="width: 20%;"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle" style="width: 20%;"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle" style="width: 20%;"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle" style="width: 30%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr class="vapor-item">
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                </table>
            </div>

            <div style="font-size: 12px; margin: 5px 0 20px 0;"><u><b>The Following Tests were Conducted at the Facility:</b></u></div>

            <div style="border: 1px solid black; padding: 2px; margin-bottom: 20px;">
                <table class="table" style="font-size: 11px; margin-top: 0px; margin-bottom: 2px; line-height: 14px; text-align: center;">
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
                        <td class="align-middle" style="width: 12%; padding: 3px;"><b>Number</b></td>
                        <td class="align-middle" style="width: 29%; padding: 3px;"><b>Test Procedure <br>Name</b></td>
                        <td class="align-middle" style="width: 15%; padding: 3px;"><b>Date Tested</b></td>
                        <td class="align-middle" style="width: 29%; padding: 3px;"><b>Name of Person(s) Conducting Test</b></td>
                        <td class="align-middle" style="width: 15%; padding: 3px;"><b>Pass or Fail</b></td>
                    </tr>
                </table>
                <table class="table" style="font-size: 11px; margin-top: 0px; margin-bottom: 0; line-height: 14px; text-align: center;">
                    <thead>
                        <tr style="display: none;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tr class="test-item">
                        <td class="align-middle" style="width: 12%;"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle" style="width: 29%;"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle" style="width: 15%;"><input type="date" class="test-dt w-100" /></td>
                        <td class="align-middle" style="width: 29%;"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle" style="width: 15%;">
                            <select class="test-dt w-100">
                                <option value="">- select -</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="test-item">
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="date" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle">
                            <select class="test-dt w-100">
                                <option value="">- select -</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="test-item">
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="date" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle">
                            <select class="test-dt w-100">
                                <option value="">- select -</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="test-item">
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="date" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle">
                            <select class="test-dt w-100">
                                <option value="">- select -</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="test-item">
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="date" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle">
                            <select class="test-dt w-100">
                                <option value="">- select -</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="test-item">
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="date" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle">
                            <select class="test-dt w-100">
                                <option value="">- select -</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="test-item">
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="date" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle">
                            <select class="test-dt w-100">
                                <option value="">- select -</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="test-item">
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="date" class="test-dt w-100" /></td>
                        <td class="align-middle"><input type="text" class="test-dt w-100" /></td>
                        <td class="align-middle">
                            <select class="test-dt w-100">
                                <option value="">- select -</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        
            <div style="font-size: 12px; margin-bottom: 5px;">
                <span style="line-height: 35px;">The tester arrived on-site at <input type="time" name="arrived_at" class="test-dt" /> and departed at <input type="time" name="departed_at" class="test-dt" /> </span><br><br>
            </div>

            <div style="font-size: 12px; margin: 5px 0 20px 0;" class="text-center"><b>Form 201.3<br> Pressure/Vacuum (P/V) Vent Valve Data Sheet</b></div>
            
            <table class="table bod-2" style="width: 100%; font-size: 13px; margin-bottom: 30px; line-height: 14px; text-align: center;">
                <thead>
                    <tr style="display: none;">
                        <th></th>
                    </tr>
                </thead>
                <tr class="pv-data-item">
                    <td style="padding: 0;">
                        <table class="table bod-2" style="font-size: 13px; line-height: 14px; margin-bottom: 3px; text-align: center;">
                            <thead>
                                <tr style="display: none;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tr>
                                <td style="width: 23%; padding: 15px 2px; border: none;"><b>P/V Valve Manufacturer:</b></td>
                                <td style="width: 15%; padding: 15px 2px; border: none;"><input type="text" class="test-dt w-100" /></td>
                                <td style="width: 20%; padding: 15px 2px; border: none;"><b>Model Number:</b></td>
                                <td style="width: 20%; padding: 15px 2px; border: none;"><input type="text" class="test-dt w-100" /></td>
                                <td style="width: 10%; padding: 15px 2px; border: none;"></td>
                                <td style="width: 10%; padding: 15px 2px; border: none;">
                                    <select class="test-dt w-100">
                                        <option value="">- select -</option>
                                        <option value="Pass">Pass</option>
                                        <option value="Fail">Fail</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <table class="table bod-2" style="font-size: 13px; margin-top: -3px; margin-bottom: -2px; line-height: 14px; text-align: center;">
                            <thead>
                                <tr style="display: none;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tr>
                                <td style="width: 30%; padding: 15px 2px; border-left: none; border-right: none; text-align: left;"><b>Manufacturers Specified Positive Leak Rate (CFH):</b></td>
                                <td style="width: 18%; padding: 15px 2px; color: transparent; border-left: none;">-</td>
                                <td style="width: 34%; padding: 15px 2px; border-right: none; text-align: left;"><b>Manufacturers Specified Negative Leak Rate (CFH):</b></td>
                                <td style="width: 18%; padding: 15px 2px; color: transparent; border-left: none; border-right: none;">-</td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none; text-align: left;">Measured Positive Leak Rate (CFH):</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                                <td style="font-size: 12px; padding: 15px 2px; border-right: none; text-align: left;">Measured Negative Leak Rate (CFH):</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none; text-align: left;">Positive Cracking Pressure (in. H20)</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; color: transparent;"><b><input type="text" class="test-dt w-100" /></b></td>
                                <td style="font-size: 12px; padding: 15px 2px; border-right: none; text-align: left;">Negative Cracking Pressure (in. H20)</td>
                                <td style="font-size: 12px; padding: 15px 2px; color: transparent; border-left: none; border-right: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="table bod-2" style="width: 100%; font-size: 13px; margin-bottom: 30px; line-height: 14px; text-align: center;">
                <thead>
                    <tr style="display: none;">
                        <th></th>
                    </tr>
                </thead>
                <tr class="pv-data-item">
                    <td style="padding: 0;">
                        <table class="table bod-2" style="font-size: 13px; line-height: 14px; margin-bottom: 3px; text-align: center;">
                            <thead>
                                <tr style="display: none;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tr>
                                <td style="width: 23%; padding: 15px 2px; border: none;"><b>P/V Valve Manufacturer:</b></td>
                                <td style="width: 15%; padding: 15px 2px; border: none;"><input type="text" class="test-dt w-100" /></td>
                                <td style="width: 20%; padding: 15px 2px; border: none;"><b>Model Number:</b></td>
                                <td style="width: 20%; padding: 15px 2px; border: none;"><input type="text" class="test-dt w-100" /></td>
                                <td style="width: 10%; padding: 15px 2px; border: none;"></td>
                                <td style="width: 10%; padding: 15px 2px; border: none;">
                                    <select class="test-dt w-100">
                                        <option value="">- select -</option>
                                        <option value="Pass">Pass</option>
                                        <option value="Fail">Fail</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <table class="table bod-2" style="font-size: 13px; margin-top: -3px; margin-bottom: -2px; line-height: 14px; text-align: center;">
                            <thead>
                                <tr style="display: none;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tr>
                                <td style="width: 30%; padding: 15px 2px; border-left: none; border-right: none; text-align: left;"><b>Manufacturers Specified Positive Leak Rate (CFH):</b></td>
                                <td style="width: 18%; padding: 15px 2px; color: transparent; border-left: none;">-</td>
                                <td style="width: 34%; padding: 15px 2px; border-right: none; text-align: left;"><b>Manufacturers Specified Negative Leak Rate (CFH):</b></td>
                                <td style="width: 18%; padding: 15px 2px; color: transparent; border-left: none; border-right: none;">-</td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none; text-align: left;">Measured Positive Leak Rate (CFH):</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                                <td style="font-size: 12px; padding: 15px 2px; border-right: none; text-align: left;">Measured Negative Leak Rate (CFH):</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none; text-align: left;">Positive Cracking Pressure (in. H20)</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; color: transparent;"><b><input type="text" class="test-dt w-100" /></b></td>
                                <td style="font-size: 12px; padding: 15px 2px; border-right: none; text-align: left;">Negative Cracking Pressure (in. H20)</td>
                                <td style="font-size: 12px; padding: 15px 2px; color: transparent; border-left: none; border-right: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="table bod-2" style="width: 100%; font-size: 13px; margin-bottom: 30px; line-height: 14px; text-align: center;">
                <thead>
                    <tr style="display: none;">
                        <th></th>
                    </tr>
                </thead>
                <tr class="pv-data-item">
                    <td style="padding: 0;">
                        <table class="table bod-2" style="font-size: 13px; line-height: 14px; margin-bottom: 3px; text-align: center;">
                            <thead>
                                <tr style="display: none;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tr>
                                <td style="width: 23%; padding: 15px 2px; border: none;"><b>P/V Valve Manufacturer:</b></td>
                                <td style="width: 15%; padding: 15px 2px; border: none;"><input type="text" class="test-dt w-100" /></td>
                                <td style="width: 20%; padding: 15px 2px; border: none;"><b>Model Number:</b></td>
                                <td style="width: 20%; padding: 15px 2px; border: none;"><input type="text" class="test-dt w-100" /></td>
                                <td style="width: 10%; padding: 15px 2px; border: none;"></td>
                                <td style="width: 10%; padding: 15px 2px; border: none;">
                                    <select class="test-dt w-100">
                                        <option value="">- select -</option>
                                        <option value="Pass">Pass</option>
                                        <option value="Fail">Fail</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <table class="table bod-2" style="font-size: 13px; margin-top: -3px; margin-bottom: -2px; line-height: 14px; text-align: center;">
                            <thead>
                                <tr style="display: none;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tr>
                                <td style="width: 30%; padding: 15px 2px; border-left: none; border-right: none; text-align: left;"><b>Manufacturers Specified Positive Leak Rate (CFH):</b></td>
                                <td style="width: 18%; padding: 15px 2px; color: transparent; border-left: none;">-</td>
                                <td style="width: 34%; padding: 15px 2px; border-right: none; text-align: left;"><b>Manufacturers Specified Negative Leak Rate (CFH):</b></td>
                                <td style="width: 18%; padding: 15px 2px; color: transparent; border-left: none; border-right: none;">-</td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none; text-align: left;">Measured Positive Leak Rate (CFH):</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                                <td style="font-size: 12px; padding: 15px 2px; border-right: none; text-align: left;">Measured Negative Leak Rate (CFH):</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none; text-align: left;">Positive Cracking Pressure (in. H20)</td>
                                <td style="font-size: 12px; padding: 15px 2px; border-left: none; color: transparent;"><b><input type="text" class="test-dt w-100" /></b></td>
                                <td style="font-size: 12px; padding: 15px 2px; border-right: none; text-align: left;">Negative Cracking Pressure (in. H20)</td>
                                <td style="font-size: 12px; padding: 15px 2px; color: transparent; border-left: none; border-right: none;"><b><input type="text" class="test-dt w-100" /></b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="last-tab">
                <table class="table bod-2" style="font-size: 12px;">
                    <thead>
                        <tr style="display: none;">
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tr>
                        <td style="text-align: center;" colspan="2">SITE INFORMATION</td>
                        <td style="display:none;"></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;">
                            <div style="width: 100%; text-align: center;">GDF NAME AND ADDRESS</div>
                            <b>NAME</b>
                            <input type="text" name="gdf_name" class="w-100" />
                        </td>
                        <td><b>GDF REPRESENTATIVE</b></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;">
                            <div style="width: 100%;"><b>ADDRESS</b></div>
                            <input type="text" name="gdf_address" class="w-100" />
                        </td>
                        <td>
                            <b>GDF PHONE NO.</b>
                            <input type="text" name="gdf_phone" class="w-100" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%;">
                            <div style="width: 100%;">PERMIT CONDITIONS:</div>
                            <input type="text" name="gdf_permit" class="w-100" />
                        </td>
                        <td>
                            <b>GDF FACILITY ID:</b>
                            <input type="text" name="gdf_fac_id" class="w-100" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            OPERATING PARAMETERS<br>
                            <table style="font-size: 12px; width: 100%; margin-bottom: -2px;">
                                <thead>
                                    <tr style="display: none;">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td style="width: 40%; padding-left: 30px; border: none;">Numbers of Nozzles Served by Tank #1</td>
                                    <td style="width: 10%; text-align: right; border: none; border-bottom: 2px solid black;"><input type="text" name="tank_noz_1" class="w-100" /></td>
                                    <td style="width: 40%; padding-left: 30px; border: none;">Numbers of Nozzles Served by Tank #3</td>
                                    <td style="width: 10%; text-align: right; border: none; border-bottom: 2px solid black;"><input type="text" name="tank_noz_3" class="w-100" /></td>
                                </tr>
                                <tr>
                                    <td style="width: 40%; padding-left: 30px; border: none;">Numbers of Nozzles Served by Tank #2</td>
                                    <td style="width: 10%; text-align: right; border: none;"><input type="text" name="tank_noz_2" class="w-100" /></td>
                                    <td style="width: 40%; padding-left: 30px; border: none;">Numbers of Nozzles Served by Tank #4</td>
                                    <td style="width: 10%; text-align: right; border: none;"><input type="text" name="tank_noz_4" class="w-100" /></td>
                                </tr>
                            </table>
                        </td>
                        <td style="display:none;"></td>
                    </tr>
                </table>

                <table class="table bod-2" style="font-size: 12px; margin-top: -18px;">
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
                        <td colspan="2" style="padding: 15px 2px; border: none; border-left: 2px solid black;">TANK #:</td>
                        <td style="display:none;"></td>
                        <td style="text-align: center; padding: 15px 2px; border: none;">1</td>
                        <td style="text-align: center; padding: 15px 2px; border: none;">2</td>
                        <td style="text-align: center; padding: 15px 2px; border: none;">3</td>
                        <td style="text-align: center; padding: 15px 2px; border: none;">4</td>
                        <td style="text-align: center; padding: 15px 2px; border: none; border-right: 2px solid black;">TOTAL</td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">1 Product Grade</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">2 Actual Tank Size</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">3 Gasoline Volume</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">4 Ullage, Gallons (#2-#3)</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">5 Initial Pressure, inches H2O</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">6 Pressure after 1 minute, inches H2O</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">7 Pressure after 2 minute, inches H2O</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">8 Pressure after 3 minute, inches H2O</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">9 Pressure after 4 minute, inches H2O</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">10 Final Pressure after 5 minute, inches H2O</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                    </tr>

                    <tr class="last-item">
                        <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
                        <td style="width: 40%; border: none;">11 Allowable Final Pressure</td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                        <td style="width: 10%;"><input type="text" class="test-dt w-100" /></td>
                    </tr>
                </table>
            </div>

            <!-- end other data -->

            <input type="hidden" id="vapor-items" name="vapor_items" value="">
            <input type="hidden" id="test-items" name="test_items" value="">
            <input type="hidden" id="pv-data-items" name="pv_data_items" value="">
            <input type="hidden" id="last-items" name="last_items" value="">
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
        
        collectData('.vapor-item', '#vapor-items');
        collectData('.test-item', '#test-items');
        collectData('.pv-data-item', '#pv-data-items');
        collectData('.last-item', '#last-items');

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