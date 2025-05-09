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
</head>
<body>
<table class="table" style="font-size: 11px; margin-top: -3px; line-height: 14px;">
      <tr>
        <td colspan="4" style="font-weight: 700; font-size: 12px; padding: 20px; background: #d9d9d9;"><center>CATHODIC PROTECTION SYSTEM EVALUATION</center></td>
      </tr>
      <tr>
        <td colspan="4" style="font-size: 9px; font-weight: 700; line-height: 17px;">
          > This form may be utilized to evaluate underground storage tank (UST) cathodic protection systems in the State of Texas.<br>
          > Checked for operation is taken to mean that it was confirmed the rectifier was receiving power and is "turned on".<br>
          > Any significant variance should be reported to your corrosion professional so that any repairs and/or adjustments necessary can be made
        </td>
      </tr>
      <tr> 
        <td colspan="2" style="font-weight: 700; background: #d9d9d9; width: 48%"><center>UST OWNER</center></td>
        <td colspan="2" style="font-weight: 700; background: #d9d9d9; width: 52%"><center>UST FACILITY</center></td>
      </tr>
      <tr>
        <td colspan="2"><b>NAME:</b> {{ $testing->customer->own_name }}</td>
        <td style=""><b>NAME:</b> {{ $testing->customer->name }}</td>
        <td style=""><b>FAC. ID #:</b> {{ $testing->customer->fac_id }}</td>
      </tr>
      <tr>
        <td colspan="2"><b>ADDRESS:</b> {{ $testing->customer->str_addr }}</td>
        <td colspan="2"><b>ADDRESS:</b> {{ $testing->customer->str_addr }}</td>
      </tr>
      <tr>
        <td style="width: 30%;"><b>CITY:</b></td>
        <td style="width: 15%;"><b>STATE:</b></td>
        <td style="width: 28%;"><b>CITY:</b></td>
        <td style="width: 27%;"><b>COUNTY:</b></td>
      </tr>
      <tr>
        <td colspan="2" style="font-weight: 700; background: #d9d9d9; width: 48%"><center>CP TESTER</center></td>
        <td colspan="2" style="font-weight: 700; background: #d9d9d9; width: 52%"><center>CP TESTER'S QUALIFICATIONS</center></td>
      </tr>
      <tr>
        <td colspan="2"><b>NAME: {{ $testing->technician->name }}</b></td>
        <td colspan="2"><b>STEEL TANK INSTITUTE ID#:</b></td>
      </tr>
      <tr>
        <td colspan="2"><b>COMPANY:</b></td>
        <td colspan="2"><b>OTHER: (EXPLAIN):</b></td>
      </tr>
      <tr>
        <td colspan="2"><b>ADDRESS:</b></td>
        <td colspan="2" style="color: transparent;">-</td>
      </tr>
      <tr>
        <td><b>CITY:</b></td>
        <td><b>STATE:</b></td>
        <td colspan="2" style="color: transparent;">-</td>
      </tr>
      <tr>
        <td colspan="4" style="font-weight: 700; background: #d9d9d9; width: 48%"><center>REASON SURVEY WAS CONDUCTED (mark only one)</center></td>
      </tr>
      <tr>        
        <td colspan="4" style="font-size: 9px; padding: 15px 0;">
          <table class="no-bd">
            <tr>
              <td><div class="black-box {{ $testing->reason == '3yrs' ? 'filled' : '' }}"></div></td>
              <td class="ps-3 pe-4">Routine - 3 year</td>
              <td><div class="black-box {{ $testing->reason == '6mnths' ? 'filled' : '' }}"></div></td>
              <td class="ps-3 pe-4">Routine - within 6 months of installatio</td>
              <td><div class="black-box {{ $testing->reason == 'after-mod' ? 'filled' : '' }}"></div></td>
              <td class="ps-3">Re-survey after repair/modification</td>
            </tr>
            <tr>
              <td colspan="6">Date next cathodic protection survey must be conducted by: {{ \Carbon\Carbon::parse($testing->conducted_date)->format('m/d/Y') }}</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="font-weight: 700; background: #d9d9d9; width: 48%"><center>CATHODIC PROTECTION TESTER'S EVALUATION (mark only one)</center></td>
      </tr>
      <tr>        
        <td colspan="4" style="padding: 15px 0;">
          <table class="no-bd">
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->evaluation == 'PASS' ? 'filled' : '' }}"></div>
              </td>
              <td class="align-top" style="width: 130px; text-align: center;">
                <div>PASS</div>
              </td>
              <td>
                <span>All protected structures at this facility "pass" the cathodic protection survey and it is judged that adequate cathodic protection has been provided to the UST system.</span>
              </td>
            </tr>
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->evaluation == 'FAIL' ? 'filled' : '' }}"></div>
              </td>
              <td class="align-top" style="width: 130px; text-align: center;">
                <div>FAIL</div>
              </td>
              <td>
                <span>One or more protected structures at this facility "fail" the cathodic protection survey and it is judged that adequate cathodic protection has not been provided to the UST system.</span>
              </td>
            </tr>
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->evaluation == 'INCONCLUSIVE' ? 'filled' : '' }}"></div>
              </td>
              <td class="align-top" style="width: 130px; text-align: center;">
                <div>INCONCLUSIVE</div>
              </td>
              <td>
                <span>The cathodic protection survey of an impressed current system must be evaluated by a "corrosion expert" because one or more of the conditions are applicable.</span>
              </td>
            </tr>
          </table>          
        </td>
      </tr>
      <tr>        
        <td colspan="4" style="padding: 17px 0;">
          <table class="no-bd" style="width: 100%;">
            <tr>
              <td colspan="2" class="align-top" style="width: 45%; font-size: 10px;"><b>CP TESTER'S SIGNATURE:</b></td>
              <td colspan="2" class="align-top" style="width: 45%; font-size: 10px;"><b>DATE CP SURVEY PERFORMED:</b> {{ \Carbon\Carbon::parse($testing->date)->format('m/d/Y') }}</td>
            </tr>
          </table>          
        </td>
      </tr>
      <tr>
        <td colspan="4" style="font-weight: 700; background: #d9d9d9; width: 48%"><center>CRITERIA APPLICABLE TO EVALUATION (mark all that apply)</center></td>
      </tr>      
      <tr>
      <td colspan="4" style="padding: 15px 0;">
          <table class="no-bd">
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->criteria_appli == 1 ? 'filled' : '' }}"></div>
              </td>
              <td>
                <span>Structure-to-soil potential more negative than -850 mV with respect to a Cu/CuSO₄ reference electrode with protective current applied (galvanic).</span>
              </td>
            </tr>
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->criteria_appli == 2 ? 'filled' : '' }}"></div>
              </td>
              <td>
                <span>Structure-to-soil potential more negative than -850 mV with respect to a Cu/CuSO₄ reference electrode with protective current momentarily Interrupted ("instant-off").</span>
              </td>
            </tr>
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->criteria_appli == 3 ? 'filled' : '' }}"></div>
              </td>
              <td>
                <span>Structure tested exhibits at least 100 mV of cathodic polarization ("100 mV polarization)</span>
              </td>
            </tr>
          </table>          
        </td>
      </tr>
      <tr>
        <td colspan="4" style="font-weight: 700; background: #d9d9d9; width: 48%"><center>ACTION REQUIRED AS A RESULT OF THIS EVALUATION (mark only one)</center></td>
      </tr>
      <tr>
      <td colspan="4" style="padding: 15px 0;">
          <table class="no-bd">
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->action_req == 'NONE' ? 'filled' : '' }}"></div>
              </td>
              <td class="align-top" style="width: 130px; text-align: center;">
                <div>NONE</div>
              </td>
              <td>
                <span>Cathodic protection has been judged adequate. No further action is necessary at this time</span>
              </td>
            </tr>
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->action_req == 'RETEST' ? 'filled' : '' }}"></div>
              </td>
              <td class="align-top" style="width: 130px; text-align: center;">
                <div>RETEST</div>
              </td>
              <td>
                <span>Cathodic protection has been judged inadequate. Retest during the next 90 days to determine if "passing" results can be achieved</span>
              </td>
            </tr>
            <tr>
              <td class="align-top" style="width: 30px;">
                <div class="black-box {{ $testing->action_req == 'REPAIR' ? 'filled' : '' }}"></div>
              </td>
              <td class="align-top" style="width: 130px; text-align: center;">
                <div>REPAIR & RETEST</div>
              </td>
              <td>
                <span>Cathodic protection has been judged inadequate. Repair/modification of the cathodic protection system is necessary</span>
              </td>
            </tr>
          </table>          
        </td>
      </tr>
    </table>

    @php
        $tank_des_items = json_decode($testing->tank_des_items, true);
        $event_des_items = json_decode($testing->event_des_items, true);
        $result_items = json_decode($testing->result_items, true);
    @endphp 

    <table class="table" style="font-size: 11px; margin-top: -17px; line-height: 14px; text-align: center;">
        <tr>
          <td colspan="5" style="font-weight: 700; background: #d9d9d9; width: 48%"><center>DESCRIPTION OF UST SYSTEM</center></td>
        </tr>
        <tr>
          <td style="width: 10%;"><b>TANK #</b></td>
          <td style="width: 18%;"><b>PRODUCT</b></td>
          <td style="width: 18%;"><b>CAPACITY</b></td>
          <td style="width: 18%;"><b>TANK MATERIAL</b></td>
          <td style="width: 36%;"><b>PIPING MATERIAL</b></td>
        </tr>
        @foreach($tank_des_items as $item)
          @php
              $isEmpty = empty(array_filter($item, fn($value) => $value !== ''));
          @endphp
          <tr style="text-align: center;">
            <td style="{{ $isEmpty ? 'color: transparent;' : '' }}">{{ $item[0] }}{{ $isEmpty ? '-' : '' }}</td> <td>{{ $item[1] }}</td> <td>{{ $item[2] }}</td> <td>{{ $item[3] }}</td> <td>{{ $item[4] }}</td>
          </tr>
        @endforeach
    </table>

    <table class="table" style="font-size: 11px; margin-top: -17px; line-height: 14px; text-align: center;">
        <tr>
          <td colspan="8" style="font-weight: 700; background: #d9d9d9;"><center>IMPRESSED CURRENT RECTIFIER DATA</center></td>
        </tr>
        <tr style="font-size: 8px;">
          <td colspan="2" style="text-align: left;"><b>Rectifier Manufacturer:</b></td>
          <td colspan="3" class="text-center">{{ $testing->rec_man }}</td>
          <td colspan="3" class="text-center"><b>Rectifier Serial Number:</b> {{ $testing->rec_serial }}</td>
        </tr>
        <tr style="font-size: 8px;">
          <td colspan="2" style="text-align: left;"><b>Rectifier Model:</b></td>
          <td colspan="2" class="text-center">{{ $testing->rec_model }}</td>
          <td colspan="4" class="text-center"><b>Rated DC Output:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $testing->rec_volt }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $testing->rec_amp }}</td>
        </tr>
        <tr>
          <td class="align-middle" rowspan="2" style="width: 10%"><b>EVENT</b></td>
          <td class="align-middle" rowspan="2" style="width: 9%"><b>DATE</b></td>
          <td colspan="2"><b>TAP SETTINGS</b></td>
          <td colspan="2"><b>DC OUTPUT</b></td>
          <td class="align-middle" rowspan="2" style="width: 9%"><b>HOUR METER</b></td>
          <td class="align-middle" rowspan="2"><b>COMMENTS</b></td>
        </tr>
        <tr>
          <td style="width: 9%"><b>COARSE</b></td>
          <td style="width: 9%"><b>FINE</b></td>
          <td style="width: 9%"><b>VOLTS</b></td>
          <td style="width: 9%"><b>AMPS</b></td>
        </tr>
        @foreach($event_des_items as $item)
          @php
              $isEmpty = empty(array_filter($item, fn($value) => $value !== ''));
          @endphp
          <tr style="text-align: center;">
            <td style="{{ $isEmpty ? 'color: transparent;' : '' }}">{{ $item[0] }}{{ $isEmpty ? '-' : '' }}</td> <td>{{ $item[1] }}</td> <td>{{ $item[2] }}</td> <td>{{ $item[3] }}</td> <td>{{ $item[4] }}</td> <td>{{ $item[5] }}</td> <td>{{ $item[6] }}</td> <td>{{ $item[7] }}</td>
          </tr>
        @endforeach
    </table>

    <table class="table" style="font-size: 11px; margin-top: -17px; line-height: 14px; text-align: center;">
        <tr>
          <td colspan="6" style="font-weight: 700; background: #d9d9d9;"><center>CONTINUITY SURVEY</center></td>
        </tr>
        <tr style="font-size: 8px; text-align: left;">
          <td colspan="6"><b>DESCRIBE LOCATION OF "FIXED REMOTE" REFERENCE ELECTRODE PLACEMENT:<b></td>
        </tr>
        <tr style="font-size: 9px;">
          <td class="align-middle" style="width: 19%;"><b>STRUCTURE A</b></td>
          <td class="align-middle" style="width: 27%;"><b>STRUCTURE B</b></td>
          <td class="align-middle" style="width: 9%;"><b>STRUCTURE "A" FIXED REMOTE INSTANT OFF VOLTAGE</b></td>
          <td class="align-middle" style="width: 9%;"><b>STRUCTURE "B" FIXED REMOTE INSTANT OFF VOLTAGE</b></td>
          <td class="align-middle" style="width: 20%;"><b>POINT-TO-POINT VOLTAGE DIFFERENCE</b></td>
          <td class="align-middle"><b>ISOLATED/ CONTINUOUS/ INCONCLUSIVE</b></td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
        </tr>
    </table>

    <table class="table" style="font-size: 11px; margin-top: -17px; line-height: 14px; text-align: center;">
        <tr>
          <td colspan="7" style="font-weight: 700; background: #d9d9d9;"><center>IMPRESSED CURRENT CATHODIC PROTECTION SYSTEM SURVEY RESULTS</center></td>
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
        @foreach($result_items as $item)
          @php
              $isEmpty = empty(array_filter($item, fn($value) => $value !== ''));
          @endphp
          <tr style="text-align: center;">
            <td style="{{ $isEmpty ? 'color: transparent;' : '' }}">{{ $item[0] }}{{ $isEmpty ? '-' : '' }}</td> <td>{{ $item[1] }}</td> <td>{{ $item[2] }}</td> <td>{{ $item[3] }}</td> <td>{{ $item[4] }}</td> <td>{{ $item[5] }}</td> <td>{{ $item[6] }}</td>
          </tr>
        @endforeach
    </table>

    <table class="table" style="font-size: 11px; line-height: 14px;">
        <tr>
          <td style="font-weight: 700; background: #d9d9d9; width: 48%">COMMENTS:</td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
        </tr>
        <tr style="color: transparent;">
          <td>-</td>
        </tr>

    </table>

</body>
</html>