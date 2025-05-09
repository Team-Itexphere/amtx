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
        
        .no-bd {
            border: none;
        }

        .table th, .table td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 6.5px 4px;
        }

        .table.entry th, .table.entry td {
            border: none;
        }       

        .table1 td {
          padding: 1;
        }

        .last-tab td {
          padding: 2px;
        }

        .bod-2 td {
          border: 2px solid black;
        }
    </style>
</head>
<body>
    <table class="table entry" style="height: 100mm; font-size: 13px; margin-top: -3px; margin-bottom: 20px; text-align: center;">
      <tr>
        <td style="height: 20%; padding-top: 40px;">
          <div style="font-size: 26px; margin-bottom: 10px;">ANNUAL</div>
          <div style="font-size: 14px;"><b>{{ \Carbon\Carbon::parse($testing->date)->format('F j, Y') }}</b></div>
        </td>
      </tr>
      <tr>
        <td style="height: 23%; font-size: 24px;">{{ $testing->customer->name }} <br>{{ $testing->customer->str_addr }}</td>
      </tr>
      <tr>
        <td class="align-bottom" style="height: 40%;">
          <div style="font-size: 20px; margin-bottom: 45px;">Performed By:</div>
          <div style="width: 100%; text-align: center; margin-bottom: 40px;"><img src="./img/pts-logo.png" height="100"></div>
          <div style="font-size: 20px; margin-bottom: 0;">
            803 Summer Park Dr, Ste 150<br>
            Stafford, Tx 77469<br>
            Ph: 281.242.2687<br>
            Fx: 281.494.4336<br>
            Email: Office.AMTS@gmail.com
          </div>
        </td>
      </tr>
    </table>

  <div style="height: 270mm">
      <div style="font-size: 12px; text-align: center;">TEXAS COMMISSION ON ENVIRONMENTAL QUALITY</div>
      <div style="font-size: 18px; text-align: center; margin: 20px 0 10px 0;"><b>Vapor Recovery Test Result Cover Sheet</b></div>
      <div style="font-size: 9px; text-align: center;"><i>(NOTICE: Submit Test Results to the appropriate TCEQ regional office, and local program with jursidiction, within 10 working days of test completion. See<br>
      reverse side for addresses.)</i></div>
      
      <div style="font-size: 12px; margin-top: 5px;"><u><b>Tests of the Vapor Recovery System are to be conducted at the following location:</b></u></div>

      <table class="table table1" style="font-size: 12px; margin-top: 3px;">
        <tr style="border: none;">
          <td style="border: none; width: 15%; padding-left: 10px;">Facility Name:</td>
          <td style="border: none; width: 40%;"><u>{{ $testing->customer->name }}</u></td>
          <td style="border: none; width: 18%; padding-left: 10px; text-align: right;">Facility ID Number:</td>
          <td style="border: none; width: 24%; text-align: right; padding-right: 0;"><u>{{ $testing->customer->fac_id }}</u></td>
        </tr>
        <tr style="border: none;">
        <td style="border: none; padding-left: 10px;">Facility Address:</td>
          <td colspan="3" style="border: none;"><u>{{ $testing->customer->str_addr }}</u></td>
        </tr>
        <tr style="border: none;">
          <td style="border: none; padding-left: 10px;">Facility City:</td>
          <td style="border: none;">_______________________________________</td>
          <td style="border: none; padding-left: 10px; text-align: right;">State: __________</td>
          <td style="border: none; padding-right: 0; text-align: right;">Zip Code: _____________</td>
        </tr>
        <tr style="border: none;">
          <td style="border: none; padding-left: 10px;">Facility Phone:</td>
          <td style="border: none;"><u>{{ $testing->customer->phone }}</u></td>
          <td colspan="2" style="border: none; color: transparent;">-</td>
        </tr>
        <tr style="border: none;">
          <td style="border: none; padding-left: 10px;">Owner Name:</td>
          <td style="border: none;"><u>{{ $testing->customer->own_name }}</u></td>
          <td style="border: none; padding-left: 10px; text-align: right;">Phone Number:</td>
          <td style="border: none; text-align: right; padding-right: 0;"><u>{{ $testing->customer->phone }}</u></td>
        </tr>
      </table>

      @php
        $vapor_items = json_decode($testing->vapor_items, true);
        $test_items = json_decode($testing->test_items, true);
        $pv_data_items = json_decode($testing->pv_data_items, true);
        $last_items = json_decode($testing->last_items, true);
      @endphp

      <div style="font-size: 12px; margin: 5px 0 20px 0;"><u><b>Vapor Recovery System Installed:</b></u></div>

      <div style="border: 1px solid black; padding: 2px; margin-bottom: 20px;">
        <table class="table" style="font-size: 11px; margin-top: 0px; margin-bottom: 2px; line-height: 14px; text-align: center;">
            <tr>
              <td class="align-middle" style="width: 10%; padding: 3px;"><b>System</b></td>
              <td class="align-middle" style="width: 20%; padding: 3px;"><b>UST or AST</b></td>
              <td class="align-middle" style="width: 20%; padding: 3px;"><b>Type of System¹</b></td>
              <td class="align-middle" style="width: 20%; padding: 3px;"><b>Executive Order or Certification Number</b></td>
              <td class="align-middle" style="width: 30%; padding: 3px;"><b>Test Purpose²</b></td>
            </tr>
        </table>
        <table class="table" style="font-size: 11px; margin-top: 0px; margin-bottom: 0; line-height: 14px; text-align: center;">
            <tr>
              <td class="align-middle" style="width: 10%;">{{ $vapor_items[0][0] }}</td>
              <td class="align-middle" style="width: 20%;">{{ $vapor_items[0][1] }}</td>
              <td class="align-middle" style="width: 20%;">{{ $vapor_items[0][2] }}</td>
              <td class="align-middle" style="width: 20%;">{{ $vapor_items[0][3] }}</td>
              <td class="align-middle" style="width: 30%;">{{ $vapor_items[0][4] }}</td>
            </tr>
            <tr>
              <td class="align-middle" style="width: 10%;">{{ $vapor_items[1][0] }}</td>
              <td class="align-middle" style="width: 20%;">{{ $vapor_items[1][1] }}</td>
              <td class="align-middle" style="width: 20%;">{{ $vapor_items[1][2] }}</td>
              <td class="align-middle" style="width: 20%;">{{ $vapor_items[1][3] }}</td>
              <td class="align-middle" style="width: 30%;">{{ $vapor_items[1][4] }}</td>
            </tr>
        </table>
      </div>

      <div style="font-size: 11px; margin-bottom: 5px;">¹ &nbsp;&nbsp; Coaxial or Two-point for Stage I, Balance or Assist for Stage II.</div>
      <div style="font-size: 11px; margin-bottom: 20px;">² &nbsp;&nbsp; Test Purposes are: CI = Initial Compliance, CA = Annual Compliance, CM = After Major Modification, or 3Y = Three Year.</div>
      
      <div style="font-size: 12px; margin: 5px 0 20px 0;"><u><b>The Following Tests were Conducted at the Facility:</b></u></div>

      <div style="border: 1px solid black; padding: 2px; margin-bottom: 20px;">
        <table class="table" style="font-size: 11px; margin-top: 0px; margin-bottom: 2px; line-height: 14px; text-align: center;">
            <tr>
              <td class="align-middle" style="width: 12%; padding: 3px;"><b>Number</b></td>
              <td class="align-middle" style="width: 29%; padding: 3px;"><b>Test Procedure <br>Name</b></td>
              <td class="align-middle" style="width: 15%; padding: 3px;"><b>Date Tested</b></td>
              <td class="align-middle" style="width: 29%; padding: 3px;"><b>Name of Person(s) Conducting Test</b></td>
              <td class="align-middle" style="width: 15%; padding: 3px;"><b>Pass or Fail</b></td>
            </tr>
        </table>
        <table class="table" style="font-size: 11px; margin-top: 0px; margin-bottom: 0; line-height: 14px; text-align: center;">
            @foreach($test_items as $item)
              @php
                $isEmpty = empty(array_filter($item, fn($value) => $value !== ''));
              @endphp
              <tr>
                <td class="align-middle" style="width: 12%; padding: 0; {{ $isEmpty ? 'color: transparent;' : '' }}">{{ $item[0] }}{{ $isEmpty ? '-' : '' }}</td>
                <td class="align-middle" style="width: 29%; padding: 0;">{{ $item[1] }}</td>
                <td class="align-middle" style="width: 15%; padding: 0;">{{ $item[2] }}</td>
                <td class="align-middle" style="width: 29%; padding: 0;">{{ $item[3] }}</td>
                <td class="align-middle" style="width: 15%; padding: 0;">{{ $item[4] }}</td>
              </tr>
            @endforeach
        </table>
      </div>
    
      <div style="font-size: 12px; margin-bottom: 5px;">
        <span style="line-height: 35px;">The tester arrived on-site at <u>{{ $testing->arrived_at }}</u> and departed at <u>{{ $testing->departed_at }}</u> There are a total of <u>3</u> pages containing test results attached to this cover sheet.</span><br><br>
        <span style="font-size: 10px; line-height: 20px;">I certify that the above tests, the results of which are attached to this cover sheet, were conducted in accordance with the test procedures as outlined in the Vapor Recovery Test Procedures Handbook, and that the results submitted here are true and correct to the best of my knowledge.<br><br></span>
        <span style="line-height: 20px;">Signature of Test Contractor Responsible Party: ________ Date: ________</span><br>
        <span style="line-height: 35px;">Test Company Name: <u>{{ $testing->customer->com_to_inv }}</u> Phone Number: _________</span><br><br>
        <span style="font-size: 10px; line-height: 12px;">TCEQ-10502 (06-05-2002)</span>
      </div>
  </div> 

    <div style="font-size: 12px; margin: 5px 0 20px 0;" class="text-center"><b>Form 201.3<br> Pressure/Vacuum (P/V) Vent Valve Data Sheet</b></div>

    <table class="table" style="font-size: 12px; margin-bottom: 20px; line-height: 14px; text-align: center;">
      <tr>
        <td colspan="3" style="padding: 2px; border: none;" class="text-end">Test Date:</td>
        <td style="padding: 2px; border: none; border-bottom: 1px solid black;">-</td>
      </tr>
      <tr>
        <td colspan="3" style="padding: 2px; border: none;" class="text-end">Page</td>
        <td style="padding: 2px; border: none; border-bottom: 1px solid black;">-</td>
      </tr>
      <tr>
        <td colspan="4" style="color: transparent; padding: 0; border: none;">-</td>
      </tr>
      <tr>
        <td style="width: 12%; padding: 2px; border: none;">Facility Name:</td>
        <td style="width: 30%; padding: 2px; border: none; border-bottom: 1px solid black;">-</td>
        <td style="width: 38%; padding: 2px; border: none;" class="text-end">Facility ID Number:</td>
        <td style="padding: 2px; border: none; border-bottom: 1px solid black;">-</td>
      </tr>
    </table>
    
    <table class="table bod-2" style="width: 100%; font-size: 13px; margin-bottom: 30px; line-height: 14px; text-align: center;">
      @foreach($pv_data_items as $item)
        <tr>
          <td style="padding: 0;">
            <table class="table bod-2" style="font-size: 13px; line-height: 14px; margin-bottom: 3px; text-align: center;">
              <tr>
                <td style="width: 23%; padding: 15px 2px; border: none;"><b>P/V Valve Manufacturer:</b></td>
                <td style="width: 15%; padding: 15px 2px; border: none;">{{ $item[0] }}</td>
                <td style="width: 20%; padding: 15px 2px; border: none;"><b>Model Number:</b></td>
                <td style="width: 20%; padding: 15px 2px; border: none;">{{ $item[1] }}</td>
                <td style="width: 10%; padding: 15px 2px; border: none;"><span style="padding: 3px; {{ $item[2] == 'Pass' ? 'border: 2px solid purple; border-radius: 100%' : 'border: none;' }}">Pass</span></td>
                <td style="width: 10%; padding: 15px 2px; border: none;"><span style="padding: 3px; {{ $item[2] == 'Fail' ? 'border: 2px solid purple; border-radius: 100%' : 'border: none;' }}">Fail</span></td>
              </tr>
            </table>
            <table class="table bod-2" style="font-size: 13px; margin-top: -3px; margin-bottom: -2px; line-height: 14px; text-align: center;">
              <tr>
                <td style="width: 30%; padding: 15px 2px; border-left: none; border-right: none; text-align: left;"><b>Manufacturers Specified Positive Leak Rate (CFH):</b></td>
                <td style="width: 18%; padding: 15px 2px; border-left: none; color: transparent;">-</td>
                <td style="width: 34%; padding: 15px 2px; border-right: none; text-align: left;"><b>Manufacturers Specified Negative Leak Rate (CFH):</b></td>
                <td style="width: 18%; padding: 15px 2px; color: transparent; border-left: none; border-right: none;">-</td>
              </tr>
              <tr>
                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none; text-align: left;">Measured Positive Leak Rate (CFH):</td>
                <td style="font-size: 12px; padding: 15px 2px; border-left: none; color: transparent;"><b>{{ $item[3] }}</b></td>
                <td style="font-size: 12px; padding: 15px 2px; border-right: none; text-align: left;">Measured Negative Leak Rate (CFH):</td>
                <td style="font-size: 12px; padding: 15px 2px; color: transparent; border-left: none; border-right: none;"><b>{{ $item[4] }}</b></td>
              </tr>
              <tr>
                <td style="font-size: 12px; padding: 15px 2px; border-left: none; border-right: none; text-align: left;">Positive Cracking Pressure (in. H20)</td>
                <td style="font-size: 12px; padding: 15px 2px; border-left: none; color: transparent;"><b>{{ $item[5] }}</b></td>
                <td style="font-size: 12px; padding: 15px 2px; border-right: none; text-align: left;">Negative Cracking Pressure (in. H20)</td>
                <td style="font-size: 12px; padding: 15px 2px; color: transparent; border-left: none; border-right: none;"><b>{{ $item[6] }}</b></td>
              </tr>
            </table>
          </td>
        </tr>
      @endforeach
    </table>

    <div class="last-tab" style="height: 270mm">
        <table style="width: 100%; font-size: 12px;">
          <tr>
            <td style="width: 45%;">T-1608-11805-16</td>
            <td style="width: 55%;"><b>TP-201.3</b></td>
          </tr>
        </table>
        <table class="table bod-2" style="font-size: 12px;">
          <tr>
            <td style="text-align: center;" colspan="2">SITE INFORMATION</td>
            <td style="width: 40%;">FACILITY PARAMETERS</td>
          </tr>
          <tr>
            <td style="width: 30%;">
              <div style="width: 100%; text-align: center;">GDF NAME AND ADDRESS</div>
              <b>NAME</b><br>
              {{ $testing->gdf_name }}
            </td>
            <td style="width: 30%;"><b>GDF REPRESENTATIVE</b></td>
            <td rowspan="3">
              <center><b>PHASE II SYSTEM TYPE</b></center>
              <center>(CIRCLE ONE)</center><br>
              BALANCE<br>
              HIRT<br>
              RED JACKET<br>
              HASSTECH<br>
              HEALY<br>
              OTHER<br><br>
              MANIFOLDED? Y OR N
            </td>
          </tr>
          <tr>
            <td style="width: 30%;">
              <div style="width: 100%;"><b>ADDRESS</b></div>
              {{ $testing->gdf_address }}
            </td>
            <td style="width: 30%;">
              <b>GDF PHONE NO.</b><br>
              {{ $testing->gdf_phone }}
            </td>
          </tr>
          <tr>
            <td style="width: 30%;">
              <div style="width: 100%;">PERMIT CONDITIONS:</div>
              {{ $testing->gdf_permit }}
            </td>
            <td style="width: 30%;">
              <b>GDF FACILITY ID:</b><br>
              {{ $testing->gdf_fac_id }}
            </td>
          </tr>
          <tr>
            <td colspan="3" style="padding: 0;">
              OPERATING PARAMETERS<br>
              <table style="font-size: 12px; width: 100%; margin-bottom: -2px;">
                <tr>
                  <td style="width: 40%; padding-left: 30px; border: none;">Numbers of Nozzles Served by Tank #1</td>
                  <td style="width: 10%; text-align: right; border: none; border-bottom: 2px solid black;">{{ $testing->tank_noz_1 }}</td>
                  <td style="width: 40%; padding-left: 30px; border: none;">Numbers of Nozzles Served by Tank #3</td>
                  <td style="width: 10%; text-align: right; border: none; border-bottom: 2px solid black;">{{ $testing->tank_noz_3 }}</td>
                </tr>
                <tr>
                  <td style="width: 40%; padding-left: 30px; border: none;">Numbers of Nozzles Served by Tank #2</td>
                  <td style="width: 10%; text-align: right; border: none;">{{ $testing->tank_noz_2 }}</td>
                  <td style="width: 40%; padding-left: 30px; border: none;">Numbers of Nozzles Served by Tank #4</td>
                  <td style="width: 10%; text-align: right; border: none;">{{ $testing->tank_noz_4 }}</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="2">APPLICATION REGULATIONS:</td>
            <td>VN RECOMMENDED:</td>
          </tr>
        </table>

        <table class="table bod-2" style="font-size: 12px; margin-top: -18px;">
          <tr>
            <td colspan="2" style="padding: 15px 2px; border: none; border-left: 2px solid black;">TANK #:</td>
            <td style="text-align: center; padding: 15px 2px; border: none;">1</td>
            <td style="text-align: center; padding: 15px 2px; border: none;">2</td>
            <td style="text-align: center; padding: 15px 2px; border: none;">3</td>
            <td style="text-align: center; padding: 15px 2px; border: none;">4</td>
            <td style="text-align: center; padding: 15px 2px; border: none; border-right: 2px solid black;">TOTAL</td>
          </tr>
          
          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Product Grade</td>
            <td style="width: 10%;">{{ $last_items[0][0] }}</td>
            <td style="width: 10%;">{{ $last_items[0][1] }}</td>
            <td style="width: 10%;">{{ $last_items[0][2] }}</td>
            <td style="width: 10%;">{{ $last_items[0][3] }}</td>
            <td style="width: 10%;">{{ $last_items[0][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>
          
          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Actual Tank Size</td>
            <td style="width: 10%;">{{ $last_items[1][0] }}</td>
            <td style="width: 10%;">{{ $last_items[1][1] }}</td>
            <td style="width: 10%;">{{ $last_items[1][2] }}</td>
            <td style="width: 10%;">{{ $last_items[1][3] }}</td>
            <td style="width: 10%;">{{ $last_items[1][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>

          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Gasoline Volume</td>
            <td style="width: 10%;">{{ $last_items[2][0] }}</td>
            <td style="width: 10%;">{{ $last_items[2][1] }}</td>
            <td style="width: 10%;">{{ $last_items[2][2] }}</td>
            <td style="width: 10%;">{{ $last_items[2][3] }}</td>
            <td style="width: 10%;">{{ $last_items[2][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>
          
          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Ullage, Gallons (#2-#3)</td>
            <td style="width: 10%;">{{ $last_items[3][0] }}</td>
            <td style="width: 10%;">{{ $last_items[3][1] }}</td>
            <td style="width: 10%;">{{ $last_items[3][2] }}</td>
            <td style="width: 10%;">{{ $last_items[3][3] }}</td>
            <td style="width: 10%;">{{ $last_items[3][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>

          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Initial Pressure, inches H2O</td>
            <td style="width: 10%;">{{ $last_items[4][0] }}</td>
            <td style="width: 10%;">{{ $last_items[4][1] }}</td>
            <td style="width: 10%;">{{ $last_items[4][2] }}</td>
            <td style="width: 10%;">{{ $last_items[4][3] }}</td>
            <td style="width: 10%;">{{ $last_items[4][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>
          
          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Pressure after 1 minute, inches H2O</td>
            <td style="width: 10%;">{{ $last_items[5][0] }}</td>
            <td style="width: 10%;">{{ $last_items[5][1] }}</td>
            <td style="width: 10%;">{{ $last_items[5][2] }}</td>
            <td style="width: 10%;">{{ $last_items[5][3] }}</td>
            <td style="width: 10%;">{{ $last_items[5][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>

          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Pressure after 2 minute, inches H2O</td>
            <td style="width: 10%;">{{ $last_items[6][0] }}</td>
            <td style="width: 10%;">{{ $last_items[6][1] }}</td>
            <td style="width: 10%;">{{ $last_items[6][2] }}</td>
            <td style="width: 10%;">{{ $last_items[6][3] }}</td>
            <td style="width: 10%;">{{ $last_items[6][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>
          
          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Pressure after 3 minute, inches H2O</td>
            <td style="width: 10%;">{{ $last_items[7][0] }}</td>
            <td style="width: 10%;">{{ $last_items[7][1] }}</td>
            <td style="width: 10%;">{{ $last_items[7][2] }}</td>
            <td style="width: 10%;">{{ $last_items[7][3] }}</td>
            <td style="width: 10%;">{{ $last_items[7][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>

          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Pressure after 4 minute, inches H2O</td>
            <td style="width: 10%;">{{ $last_items[8][0] }}</td>
            <td style="width: 10%;">{{ $last_items[8][1] }}</td>
            <td style="width: 10%;">{{ $last_items[8][2] }}</td>
            <td style="width: 10%;">{{ $last_items[8][3] }}</td>
            <td style="width: 10%;">{{ $last_items[8][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>
          
          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Final Pressure after 5 minute, inches H2O</td>
            <td style="width: 10%;">{{ $last_items[9][0] }}</td>
            <td style="width: 10%;">{{ $last_items[9][1] }}</td>
            <td style="width: 10%;">{{ $last_items[9][2] }}</td>
            <td style="width: 10%;">{{ $last_items[9][3] }}</td>
            <td style="width: 10%;">{{ $last_items[9][4] }}</td>
          </tr>     
          <tr>
            <td colspan="7" style="border: none; border-left: 2px solid black; border-right: 2px solid black; color: transparent;">-</td>
          </tr>

          <tr>
            <td style="width: 10%; border: none; border-left: 2px solid black; color: transparent;">-</td>
            <td style="width: 40%; border: none;">Allowable Final Pressure</td>
            <td style="width: 10%;">{{ $last_items[10][0] }}</td>
            <td style="width: 10%;">{{ $last_items[10][1] }}</td>
            <td style="width: 10%;">{{ $last_items[10][2] }}</td>
            <td style="width: 10%;">{{ $last_items[10][3] }}</td>
            <td style="width: 10%;">{{ $last_items[10][4] }}</td>
          </tr>

        </table>

        <table class="table bod-2" style="margin-top: -18px; font-size: 12px;">
          <tr>
            <td style="width: 40%;">
              <div style="width: 100%; text-align: center;">Test Conducted by:<br>{{ $testing->technician->name }}</div>
            </td>
            <td style="width: 30%;">
              <div style="width: 100%; text-align: center;">Test Company:<br>{{ $testing->customer->com_to_inv }}</div>
            </td>
            <td style="width: 30%;">
              <div style="width: 100%; text-align: center;">Date of Test:<br>{{ \Carbon\Carbon::parse($testing->date)->format('m/d/Y') }}</div>
            </td>
          </tr>
          <tr>
            <td colspan="3">COMMENTS:</td>
          </tr>
          <tr>
            <td colspan="3" style="color: transparent;">-</td>
          </tr>
          <tr>
            <td colspan="3" style="color: transparent;">-</td>
          </tr>
        </table>
    </div>

</body>
</html>