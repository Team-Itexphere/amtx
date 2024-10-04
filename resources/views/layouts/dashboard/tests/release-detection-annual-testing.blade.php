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
            padding: 2px 4px;
        }

        .table.meta td {
            padding-top: 0px;
        }
        
    </style>
</head>
<body>
    <h6 style="font-size: 16px; font-weight: 700; text-align: right;"><b>**You may use this template to demonstrate compliance**</b></h6>
  
  <div style="padding: 0 20px;">
    <table class="table" style="font-size: 12px; margin-top: -7px; line-height: 14px;">
      <tr>
        <td style="width: 9%; padding: 0; border: none;">
          <img src="./img/tceq_lg.JPG" height="105"/>
        </td>
        <td style="width: 45%; padding: 0 70px 0 0; border: none;">
          <h6 style="font-size: 20px; font-weight: 700;"><i><b>Record of Release Detection <br>Annual Testing </b></i></h6>
          <div style="font-size: 13px;">If you have questions on how to complete this form or about the petroleum storage tank (PST) program, please contact the Small Business and Local Government Assistance Hotline at 1-800-447-2827 or visit our Web site at www.TexasEnviroHelp.org.</div>
        </td>
        <td style="width: 46%; padding: 0; border: none;">
          <table style="width: 100%;">
            <tr style="background: #e6e7e9;">
              <td colspan="2" style="border: none; font-size: 15px; font-weight: 700;">Facility Information</td>
            </tr>
            <tr>
              <td style="border: none; color: transparent; line-height: 5px;">-</td>
            </tr>
            <tr>
              <td style="width: 55%;"><b>Facility Name:</b>
                <div style="text-align: center; margin-top: 5px;">{{ $testing->customer->name }}</div>
              </td>
              <td style="width: 45%;"><b>Facility ID #:</b>
                <div style="text-align: center; margin-top: 5px;">{{ $testing->customer->fac_id }}</div>     
              </td>
            </tr>
            <tr>
              <td style="width: 55%;"><b>Street Address:</b>
                <div style="text-align: center; margin-top: 5px;">{{ $testing->customer->str_addr }}</div>
              </td>
              <td style="width: 45%;"><b>City, State, ZIP:</b>
                <div style="text-align: center; margin-top: 5px;">-</div>                 
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <table class="table" style="font-size: 13px; margin-top: -15px; line-height: 14px;">
        <tr style="background: #e6e7e9;">
          <td style="font-weight: 700; border: none;">Instructions</td>
        </tr>
        <tr>
          <td style="font-weight: 400; font-size: 12px; line-height: 16px; border: none;">
            <ul style="margin-bottom: 0;">
              <li>Your release detection equipment must be tested annually for proper operation.</li>
              <li>The code of practice that may be used is Petroleum Equipment Institute (PEI) Publication RP1200, "Recommended Practices for the Testing and Verification of Spill, Overfill, Leak Detection and Secondary Containment Equipment at UST Facilities."</li>
              <li>If an item listed in the <i>Components Tested</i> column is not applicable to your facility, record "N/A" for that item.</li>
              <li>List any additional release detection equipment in the Other Components Tested column.</li>
              <li>Have the Release Detection Tester record the test date in the space above the table, complete the testing and fill out the table below.</li>
              <li>In the last column, have the Release Detection Tester record the actions taken to fix any issues identified during the test.</li>
              <li>Have the Release Detection Tester sign and date the bottom of this form. Keep the form on file for at least 5 years.</li>
            </ul>
          </td>
        </tr>
        <tr style="background: #e6e7e9;">
          <td style="font-weight: 700; border: none;">Required Annually</td>
        </tr>
    </table>
  </div>

    <table class="table meta" style="font-size: 12px; margin-top: -5px; line-height: 14px;">
        <tr>
          <td colspan="5" style="padding: 1px 0 5px 6px; font-weight: 400; border: none;">Date(s) of annual release detection operation test: 
            @if($testing->date)
              <u>{{ \Carbon\Carbon::parse( $testing->date )->format('m/d/Y') }}</u>
            @else
              _______________
            @endif
          </td>
        </tr>
        <tr style="background: #e6e7e9;">
          <td style="width: 45%; text-align: center;"><b>Component Tested</b></td>
          <td style="width: 15%; text-align: center;"><b>Name of Tester</b></td>
          <td style="width: 10%; text-align: center;"><b>Meets Criteria? (Y/N)</b></td>
          <td style="width: 10%; text-align: center;"><b>Needs Action? (Y/N)</b></td>
          <td style="width: 20%; text-align: center;"><b>Action Taken to Correct Issue</b></td>
        </tr>
        @foreach($testing->testing_meta->take(5) as $key => $meta)
          <tr>
            <td style="padding-left: 8px; font-size: 12px;">
              <b>{{ $rda_comp_tests[$key]['test'] }}</b>
              <span>{{ $rda_comp_tests[$key]['description'] }}</span>
            </td>
            <td class="text-center">{{ $testing->technician->name }}</td>
            <td class="text-center">{{ $meta->meets_criteria }}</td>
            <td class="text-center">{{ $meta->needs_action }}</td>
            <td class="text-center">{{ $meta->action_taken ?? 'N/A' }}</td>
          </tr>
        @endforeach

        <tr style="background: #e6e7e9;">
          <td style="width: 45%; text-align: center;"><b>Other Components Tested:</b></td>
          <td style="width: 15%; text-align: center;"><b>Name of Tester</b></td>
          <td style="width: 10%; text-align: center;"><b>Meets Criteria? (Y/N)</b></td>
          <td style="width: 10%; text-align: center;"><b>Needs Action? (Y/N)</b></td>
          <td style="width: 20%; text-align: center;"><b>Action Taken to Correct Issue</b></td>
        </tr>
        @foreach($testing->testing_meta->skip(5) as $meta)
          <tr>
            <td style="padding-left: 8px; font-size: 12px;">{{ $meta->descript }}</td>
            <td class="text-center">{{ $testing->technician->name }}</td>
            <td class="text-center">{{ $meta->meets_criteria }}</td>
            <td class="text-center">{{ $meta->needs_action }}</td>
            <td class="text-center">{{ $meta->action_taken ?? 'N/A' }}</td>
          </tr>
        @endforeach

        <tr style="border: none;">
          <td colspan="5" style="padding: 10px 0 0 0; border: none; font-size: 12px;"> ___________________________________  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
            @if($testing->date)
              <u>{{ \Carbon\Carbon::parse( $testing->date )->format('m/d/Y') }}</u>
            @else
              ___________
            @endif
          </td>
        </tr>
        <tr style="border: none;">
          <td colspan="5" style="padding: 3px 0 0 0; border: none; font-size: 12px; line-height: 10px;"><b>Release Detection Tester Signature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date<b></td>
        </tr>
    </table>

    <h6 style="font-size: 16px; font-weight: 700; text-align: center;"><b>Keep this record for at least 5 years.</b></h6>

</body>
</html>