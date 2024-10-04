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
        
        .black-bar {
          background: black;
          padding: 8px;
        }

        .blue-back{
          background: #b8ddf8;
        }

        .main-cont {
          margin-bottom: 20px;
        }

        .ques-cont tr:nth-child(odd) {
          background-color: #caedfb;
        }
        
        .ques-cont td:nth-child(1) {
          width: 100mm;
        }

        .ques-cont td {
          font-size: 10px;
        }

        .file-item {
          margin: 10px;
          border: 1px solid #80808022;
          /*float: left;*/
        }

        .file-item, .file-item img {
          width: 49mm !important; 
          height: 29mm !important;
        }
        
        .line_breaker {
            margin-bottom: -20px;
            opacity: 0;
        }
        
        .spacer {
            margin-bottom: 10px;
            opacity: 0;
        }
        
        .orange {
            background: #FFC000 !important;
        }
        
        .red {
            background: #FF0000 !important;
        }
        
        .green {
            background: #84E291 !important;
        }
        
        .main-hd {
            font-size: 67%;
            font-weight: 400;
            border-bottom: 1px solid black;
        }
        
        .no-bd {
            border: none;
        }
        
        .font-12 {
            font-size: 12px !important;
            margin-bottom: 8px;
        }

        .table th, .table td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 6.5px 4px;
        }

        input {
          margin-bottom: -3.5px !important;
        }
    </style>
</head>
<body>
    <h5 class="main-hd"><center>Recommended Practices for the Testing and Verification of Spill, Overfill, Leak Detection and Secondary Containment Equipment at UST Facilities</center></h5>
    <h4 style="font-weight: 700; background: black; color: white; padding: 4px; margin-top: 12px;"><center>APPENDIX C-7</center></h4>
    
    <table class="table" style="font-size: 12px; margin-top: -3px; line-height: 14px;">
      <tr>
        <td colspan="4" style="font-size: 13px; background: black; color: white; padding: 7px; font-weight: 700;"><center>AUTOMATIC TANK GUAGE <br> OPERATION INSPECTION</center></td>
      </tr>
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
        <td colspan="2" style="width: 45%;">City, State, Zip Code:
      </td>
      </tr>
      <tr>
        <td colspan="2" style="width: 55%;">Facility I.D. #: {{ $testing->customer->fac_id }}</td>
        <td colspan="2" style="width: 45%;">Phone #: {{ $testing->customer->phone }}</td>
      </tr>
      <tr>
        <td colspan="2" style="width: 55%;">Testing Company: {{ $testing->customer->com_to_inv }}</td>
        <td style="width: 22.5%;">Phone #: {{ $testing->customer->str_phone }}</td>
        <td style="width: 22.5%;">Date: {{ $testing->date }}</td>
      </tr>
      <tr>
        <td colspan="4">This procedure is to determine whether the automatic tank guage (ATG) is operating properly. See PEI/RP1200 Section 8.2 for the inspection procedure. This procedure is applicable to tank level monitor stems that touch the bottom of the tank when in place.</td>
      </tr>
    </table>

    @php
        $test = json_decode($testing->tanks, true);
    @endphp        

    <table class="table" style="font-size: 12px; margin-top: -17px; line-height: 14px;">
        <tr>
          <td style="padding-left: 10px;">Tank Number</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px; font-weight: 700;">{{ $test[0][0] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px; font-weight: 700;">{{ $test[0][1] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px; font-weight: 700;">{{ $test[0][2] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px; font-weight: 700;">{{ $test[0][3] }}</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">Product Stored</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[1][0] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[1][1] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[1][2] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[1][3] }}</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">ATG Brand and Model</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[2][0] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[2][1] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[2][2] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[2][3] }}</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">1. Tank Volume, gallons</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[3][0] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[3][1] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[3][2] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[3][3] }}</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">2. Tank Diameter, inches</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[4][0] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[4][1] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[4][2] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[4][3] }}</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">3. After removing the ATG from the tank, it has been inspected and any damaged or missing parts replaced?</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[5][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[5][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[5][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[5][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[5][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[5][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[5][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[5][3] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">4. Float moves freely on the stem without binding?</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[6][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[6][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[6][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[6][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[6][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[6][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[6][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[6][3] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">5. Fuel float level agrees with the value programmed into the console?</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[7][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[7][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[7][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[7][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[7][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[7][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[7][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[7][3] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">6. Water float level agrees with the value programmed into the console?</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[8][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[8][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[8][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[8][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[8][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[8][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[8][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[8][3] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">7. Inch level from bottom of stem when 90% alarm is triggered.</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[9][0] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[9][1] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[9][2] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[9][3] }}</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">8. Inch level at which the overfill alarm activates corresponds with value programmed in the gauge?</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[10][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[10][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[10][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[10][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[10][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[10][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[10][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[10][3] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">9. Inch level from the bottom when the water float first triggers an alarm</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[11][0] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[11][1] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[11][2] }}</td>
          <td class="align-middle" style="width: 15%; text-align: center; font-size: 15px;">{{ $test[11][3] }}</td>
        </tr>
        <tr>
          <td style="padding-left: 10px;">10. Inch level at which the water float alarm activates corresponds with value programmed in the gauge?</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[12][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[12][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[12][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[12][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[12][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[12][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[12][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input type="checkbox" {{ $test[12][3] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td colspan="5">If any answers in Lines 3, 4, 5, or 6 are “No,” the system has failed the test.</td>
        </tr>
        <tr style="background: #e6e7e9;">
          <td>Test Results</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[13][0] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input type="checkbox" {{ $test[13][0] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[13][1] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input type="checkbox" {{ $test[13][1] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[13][2] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input type="checkbox" {{ $test[13][2] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="width: 15%; text-align: center;"><input type="checkbox" {{ $test[13][3] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input type="checkbox" {{ $test[13][3] == 'Fail' ? 'checked' : '' }}> Fail</td>
        </tr>
        <tr>
          <td colspan="5">Comments <br><br><br><br></td>
        </tr>
        <tr style="border: none;">
          <td colspan="2" style="padding: 15px 0 0 0; border: none; font-size: 13px;">Tester’s Name (print): <u>{{ $testing->technician->name }}</u></td>
          <td colspan="3" style="padding: 15px 0 0 0; text-align: right; border: none; font-size: 13px;">Tester’s Signature: ___________________________</td>
        </tr>
    </table>

</body>
</html>