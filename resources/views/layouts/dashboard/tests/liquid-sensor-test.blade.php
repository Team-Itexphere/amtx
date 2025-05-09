<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');
    </style>
    <style>
        body {
          width: 273mm;
          height: 185mm;
          font-family: "Rubik", sans-serif !important;
        }

        .table th, .table td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 4px 4px;
        }
    </style>
</head>
<body>
    <h6 style="font-size: 14px; font-weight: 700; background: black; color: white; padding: 7px;"><center>LIQUID SENSOR FUNCTIONALITY TESTING</center></h6>
    
    <table class="table" style="font-size: 12px; margin-top: -8px; line-height: 14px;">
      <tr>
        <td colspan="2" style="width: 55%;">Facility Name: {{ $testing->customer->name }}</td>
        <td colspan="2" style="width: 45%;">Owner: {{ $testing->customer->own_name }}</td>
      </tr>
      <tr>
        <td colspan="2" style="width: 55%;">Address: {{ $testing->customer->str_addr }}</td>
        <td colspan="2" style="width: 45%;">Address: {{ $testing->customer->str_addr }}</td>
      </tr>
      <tr>
        <td colspan="2" style="width: 55%;">City, State, Zip Code:</td>
        <td colspan="2" style="width: 45%;">City, State, Zip Code:</td>
      </tr>
      <tr>
        <td colspan="2" style="width: 55%;">Facility I.D. #: {{ $testing->customer->fac_id }}</td>
        <td colspan="2" style="width: 45%;">Phone #: {{ $testing->customer->phone }}</td>
      </tr>
      <tr>
        <td colspan="2" style="width: 55%;">Testing Company: {{ $testing->customer->com_to_inv }}</td>
        <td style="width: 22.5%;">Phone #:</td>
        <td style="width: 22.5%;">Date: {{ \Carbon\Carbon::parse($testing->date)->format('m/d/Y') }}</td>
      </tr>
      <tr>
        <td colspan="4">This procedure is to determine whether liquid sensors located in the interstitial space of UST systems are able to detect the presence of water and fuel. See PEI/RP1200 Section 8.3 for the test procedure.</td>
      </tr>
    </table>

    @php
        $test = json_decode($testing->items, true);
    @endphp

    <table class="table" style="font-size: 12px; margin-top: -17px; line-height: 14px;">
        <tr>
          <td>Sensor Location</td>
          <td style="width: 10%; text-align: center;">{{ $test[0][0] }}</td>
          <td style="width: 10%; text-align: center;">{{ $test[0][1] }}</td>
          <td style="width: 10%; text-align: center;">{{ $test[0][2] }}</td>
          <td style="width: 10%; text-align: center;">{{ $test[0][3] }}</td>
          <td style="width: 10%; text-align: center;">{{ $test[0][4] }}</td>
          <td style="width: 10%; text-align: center;">{{ $test[0][5] }}</td>
          <td style="width: 10%; text-align: center;">{{ $test[0][6] }}</td>
        </tr>
        <tr>
          <td>Sensor Location</td>
          <td style="text-align: center;">{{ $test[1][0] }}</td>
          <td style="text-align: center;">{{ $test[1][1] }}</td>
          <td style="text-align: center;">{{ $test[1][2] }}</td>
          <td style="text-align: center;">{{ $test[1][3] }}</td>
          <td style="text-align: center;">{{ $test[1][4] }}</td>
          <td style="text-align: center;">{{ $test[1][5] }}</td>
          <td style="text-align: center;">{{ $test[1][6] }}</td>
        </tr>
        <tr>
          <td>Test Liquid</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][0] == 'Yes' ? 'checked' : '' }}> Discriminating <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][0] == 'No' ? 'checked' : '' }}> Non-discriminating</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][1] == 'Yes' ? 'checked' : '' }}> Discriminating <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][1] == 'No' ? 'checked' : '' }}> Non-discriminating</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][2] == 'Yes' ? 'checked' : '' }}> Discriminating <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][2] == 'No' ? 'checked' : '' }}> Non-discriminating</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][3] == 'Yes' ? 'checked' : '' }}> Discriminating <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][3] == 'No' ? 'checked' : '' }}> Non-discriminating</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][4] == 'Yes' ? 'checked' : '' }}> Discriminating <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][4] == 'No' ? 'checked' : '' }}> Non-discriminating</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][5] == 'Yes' ? 'checked' : '' }}> Discriminating <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][5] == 'No' ? 'checked' : '' }}> Non-discriminating</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[2][6] == 'Yes' ? 'checked' : '' }}> Discriminating <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[2][6] == 'No' ? 'checked' : '' }}> Non-discriminating</td>
        </tr>
        <tr>
          <td>Type of Sensor</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[3][0] == 'Yes' ? 'checked' : '' }}> Water &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][0] == 'No' ? 'checked' : '' }}> Product</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[3][1] == 'Yes' ? 'checked' : '' }}> Water &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][1] == 'No' ? 'checked' : '' }}> Product</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[3][2] == 'Yes' ? 'checked' : '' }}> Water &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][2] == 'No' ? 'checked' : '' }}> Product</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[3][3] == 'Yes' ? 'checked' : '' }}> Water &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][3] == 'No' ? 'checked' : '' }}> Product</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[3][4] == 'Yes' ? 'checked' : '' }}> Water &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][4] == 'No' ? 'checked' : '' }}> Product</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[3][5] == 'Yes' ? 'checked' : '' }}> Water &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][5] == 'No' ? 'checked' : '' }}> Product</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[3][6] == 'Yes' ? 'checked' : '' }}> Water &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[3][6] == 'No' ? 'checked' : '' }}> Product</td>
        </tr>        
        <tr>
          <td>Is the ATG console clear of any active or recurring warnings or alarms regarding the leak sensor? If the sensor is in alarm and functioning, indicate why</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][5] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][6] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][6] == 'No' ? 'checked' : '' }}> No</td>
        </tr>        
        <tr>
          <td>Is the sensor alarm circuit operational?</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][5] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][6] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][6] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td>Has sensor been inspected and in good operating condition?</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][5] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][6] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][6] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td>When placed in the test liquid, does the sensor trigger an alarm?</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][5] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][6] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][6] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td>When an alarm is triggered, is the sensor properly identified on the ATG console?</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][5] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][6] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][6] == 'No' ? 'checked' : '' }}> No</td>
        </tr>

        <tr>
          <td colspan="8">Any “No” answers indicates the sensor fails the test.</td>
        </tr>
        <tr style="background: #e6e7e9;">
          <td style="font-weight: 700;">Test Results</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][0] == 'Pass'  ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][0] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][1] == 'Pass'  ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][1] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][2] == 'Pass'  ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][2] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][3] == 'Pass'  ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][3] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][4] == 'Pass'  ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][4] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][5] == 'Pass'  ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][5] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][6] == 'Pass'  ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][6] == 'Fail' ? 'checked' : '' }}> Fail</td>
        </tr>
        <tr>
          <td colspan="8" style="font-weight: 700;">Comments <br><br><br><br></td>
        </tr>
        <tr style="border: none;">
          <td colspan="3" style="padding: 15px 0 0 0; border: none; font-size: 13px;">Tester’s Name (print) <u>{{ $testing->technician->name }}</u></td>
          <td colspan="5" style="padding: 15px 0 0 0; border: none; font-size: 13px;">Tester’s Signature _________________________________________</td>
        </tr>
    </table>

</body>
</html>