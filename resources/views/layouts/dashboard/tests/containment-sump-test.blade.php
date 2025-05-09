<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');
    </style>
    <style>
        body {
          width: 185mm;
          height: 297mm;
          font-family: "Rubik", sans-serif !important;
        }
        
        .table th, .table td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 2px 3px;
        }

        .table.sec th, .table.sec td {
            padding: 4px 3px;
        }
    </style>
</head>
<body>
    <table class="table" style="font-size: 12px; margin-top: -3px; line-height: 14px;">
      <tr>
        <td colspan="7" style="font-size: 13px; font-weight: 700;"><center>CONTAINMENT SUMP INTEGRITY TESTING</center></td>
      </tr>
      <tr>
        <td colspan="7" style="font-size: 13px; font-weight: 700;"><center>HYDROSTATIC TESTING METHOD</center></td>
      </tr>
      <tr>
        <td colspan="7" style="color: transparent;">-</td>
      </tr>
      <tr>
        <td colspan="2">Facility Name:</td>
        <td style="border-left: none; text-align: center;">{{ $testing->customer->name }}</td>
        <td style="width: 10.5%;">Owner:</td>
        <td colspan="3" style="text-align: center;">{{ $testing->customer->own_name }}</td>
      </tr>
      <tr>
        <td style="width: 10.5%;">Address:</td>
        <td colspan="2" style="text-align: center;">{{ $testing->customer->str_addr }}</td>
        <td>Address:</td>
        <td colspan="3" style="width: 40%; text-align: center;">-</td>
      </tr>
      <tr>
        <td colspan="2">City, State, Zip Code:</td>
        <td style="width: 26.5%; text-align: center;">-</td>
        <td colspan="2">City, State, Zip Code:
        <td colspan="2" style="text-align: center;">-</td>
      </td>
      </tr>
      <tr>
        <td colspan="2">Facility I.D. #:</td>
        <td style="text-align: center;">{{ $testing->customer->fac_id }}</td>
        <td>Phone #:</td>
        <td colspan="3" style="text-align: center;">{{ $testing->customer->phone }}</td>
      </tr>
      <tr>
        <td colspan="2">Testing Company:</td>
        <td style="text-align: center;">{{ $testing->customer->com_to_inv }}</td>
        <td>Phone #:</td>
        <td colspan="2" style="text-align: center;">{{ $testing->customer->phone }}</td>
        <td style="width: 21%;">Date: {{ \Carbon\Carbon::parse($testing->date)->format('m/d/Y') }}</td>
      </tr>
      <tr>
        <td colspan="7" style="color: transparent;">-</td>
      </tr>
      <tr>
        <td colspan="7">This procedure is to test the leak integrity of containment sumps. See PEI/RP1200, Section 6.5 for the test method.</td>
      </tr>
      <tr>
        <td colspan="7" style="color: transparent;">-</td>
      </tr>
    </table>
    
    @php
        $test = json_decode($testing->items, true);
    @endphp 

    <table class="table sec" style="font-size: 15px; margin-top: -17px; line-height: 14px;">
        <tr>
          <td>Containment Sump ID</td>
          <td style="width: 10.5%; text-align: center;">{{ $test[0][0] }}</td>
          <td style="width: 10.5%; text-align: center;">{{ $test[0][1] }}</td>
          <td style="width: 10.5%; text-align: center;">{{ $test[0][2] }}</td>
          <td style="width: 10.5%; text-align: center;">{{ $test[0][3] }}</td>
          <td style="width: 10.5%; text-align: center;">{{ $test[0][4] }}</td>
          <td style="width: 10.5%; text-align: center;">{{ $test[0][5] }}</td>
        </tr>
        <tr>
          <td>Containment Sump Material</td>
          <td style="text-align: center;">{{ $test[1][0] }}</td>
          <td style="text-align: center;">{{ $test[1][1] }}</td>
          <td style="text-align: center;">{{ $test[1][2] }}</td>
          <td style="text-align: center;">{{ $test[1][3] }}</td>
          <td style="text-align: center;">{{ $test[1][4] }}</td>
          <td style="text-align: center;">{{ $test[1][5] }}</td>
        </tr>
        <tr>
          <td>Liquid and debris removed from sumps?*</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][5] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td>Visual inspection (No cracks, loose parts or separation of the containment sump.)</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][5] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td>Containment Sump Depth</td>
          <td style="text-align: center;">{{ $test[4][0] }}</td>
          <td style="text-align: center;">{{ $test[4][1] }}</td>
          <td style="text-align: center;">{{ $test[4][2] }}</td>
          <td style="text-align: center;">{{ $test[4][3] }}</td>
          <td style="text-align: center;">{{ $test[4][4] }}</td>
          <td style="text-align: center;">{{ $test[4][5] }}</td>
        </tr>
        <tr>
          <td>Height from Bottom to Top of highest penetration</td>
          <td style="text-align: center;">{{ $test[5][0] }}</td>
          <td style="text-align: center;">{{ $test[5][1] }}</td>
          <td style="text-align: center;">{{ $test[5][2] }}</td>
          <td style="text-align: center;">{{ $test[5][3] }}</td>
          <td style="text-align: center;">{{ $test[5][4] }}</td>
          <td style="text-align: center;">{{ $test[5][5] }}</td>
        </tr>
        <tr>
          <td>Starting water level</td>
          <td style="text-align: center;">{{ $test[6][0] }}</td>
          <td style="text-align: center;">{{ $test[6][1] }}</td>
          <td style="text-align: center;">{{ $test[6][2] }}</td>
          <td style="text-align: center;">{{ $test[6][3] }}</td>
          <td style="text-align: center;">{{ $test[6][4] }}</td>
          <td style="text-align: center;">{{ $test[6][5] }}</td>
        </tr>
        <tr>
          <td>Test Start Time</td>
          <td style="text-align: center;">{{ $test[7][0] }}</td>
          <td style="text-align: center;">{{ $test[7][1] }}</td>
          <td style="text-align: center;">{{ $test[7][2] }}</td>
          <td style="text-align: center;">{{ $test[7][3] }}</td>
          <td style="text-align: center;">{{ $test[7][4] }}</td>
          <td style="text-align: center;">{{ $test[7][5] }}</td>
        </tr>
        <tr>
          <td>Ending Water Level</td>
          <td style="text-align: center;">{{ $test[8][0] }}</td>
          <td style="text-align: center;">{{ $test[8][1] }}</td>
          <td style="text-align: center;">{{ $test[8][2] }}</td>
          <td style="text-align: center;">{{ $test[8][3] }}</td>
          <td style="text-align: center;">{{ $test[8][4] }}</td>
          <td style="text-align: center;">{{ $test[8][5] }}</td>
        </tr>
        <tr>
          <td>Test End Time</td>
          <td style="text-align: center;">{{ $test[9][0] }}</td>
          <td style="text-align: center;">{{ $test[9][1] }}</td>
          <td style="text-align: center;">{{ $test[9][2] }}</td>
          <td style="text-align: center;">{{ $test[9][3] }}</td>
          <td style="text-align: center;">{{ $test[9][4] }}</td>
          <td style="text-align: center;">{{ $test[9][5] }}</td>
        </tr>
        <tr>
          <td>Test Period (Minimum test time: 1 hour)</td>
          <td style="text-align: center;">{{ $test[10][0] }}</td>
          <td style="text-align: center;">{{ $test[10][1] }}</td>
          <td style="text-align: center;">{{ $test[10][2] }}</td>
          <td style="text-align: center;">{{ $test[10][3] }}</td>
          <td style="text-align: center;">{{ $test[10][4] }}</td>
          <td style="text-align: center;">{{ $test[10][5] }}</td>
        </tr>
        <tr>
          <td>Water Level Change</td>
          <td style="text-align: center;">{{ $test[11][0] }}</td>
          <td style="text-align: center;">{{ $test[11][1] }}</td>
          <td style="text-align: center;">{{ $test[11][2] }}</td>
          <td style="text-align: center;">{{ $test[11][3] }}</td>
          <td style="text-align: center;">{{ $test[11][4] }}</td>
          <td style="text-align: center;">{{ $test[11][5] }}</td>
        </tr>
        <tr>
          <td colspan="7">Pass/Fail criteria: Must pass visual inspection. Water level drop of less than 1/8 inch.</td>
        </tr>
        <tr>
          <td style="font-weight: 700;">Test Results</td>
          <td class="align-middle" style="width: 10.5%; text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][0] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][0] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="width: 10.5%; text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][1] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][1] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="width: 10.5%; text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][2] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][2] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="width: 10.5%; text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][3] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][3] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="width: 10.5%; text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][4] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][4] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="width: 10.5%; text-align: center; font-size: 9px;"><input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][5] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[12][5] == 'Fail' ? 'checked' : '' }}> Fail</td>
        </tr>
        <tr>
          <td colspan="7" style="font-weight: 700;">Comments:</td>
        </tr>
        <tr>
          <td colspan="7" style="color: transparent; padding: 2px;">-</td>
        </tr>
        <tr>
          <td colspan="7" style="color: transparent; padding: 2px;">-</td>
        </tr>
        <tr>
          <td colspan="7" style="color: transparent; padding: 2px;">-</td>
        </tr>
        <tr>
          <td colspan="7" style="color: transparent; padding: 2px;">-</td>
        </tr>
        <tr>
          <td colspan="7" style="padding: 3px; border: none; font-size: 12px;">* All liquids and debris must be disposed of property.</td>
        </tr>
        <tr style="border: none;">
          <td colspan="2" style="padding: 10px 0 0 0; border: none; font-size: 15px;">Tester’s Name <u>{{ $testing->technician->name }}</u></td>
          <td colspan="5" style="padding: 10px 0 0 0; text-align: right; border: none; font-size: 15px;">Tester’s Signature ___________________________</td>
        </tr>
    </table>

</body>
</html>