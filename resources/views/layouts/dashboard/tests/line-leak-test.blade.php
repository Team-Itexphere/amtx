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
        
        .font-12 {
            font-size: 12px !important;
            margin-bottom: 8px;
        }

        .table th, .table td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 6.5px 4px;
        }
    </style>
</head>
<body>
    <div style="width: 100%; text-align: center;"><img src="./img/pts-logo.png" height="85"></div>
    <h5 class="main-hd"><center>LINE AND LEAK DETECTOR TEST DATA FORM</center></h5>
    
    <table class="table" style="font-size: 13px; margin-top: -3px; font-weight: 600;">
      <tr style="border: none;">
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px; ">Facility Name:</td>
        <td style="width: 35%; padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>{{ $testing->customer->name }}</center></td>
        <td class="text-end" style="width: 20%; padding: 15px 0 0 0; border: none; font-size: 13px;">Date:</td>
        <td style="width: 30%; padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>{{ \Carbon\Carbon::parse($testing->date)->format('m/d/Y') }}</center></td>
      </tr>
      <tr style="border: none;">
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;">Facility Address:</td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>{{ $testing->customer->str_addr }}</center></td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
      </tr>
      <tr style="border: none;">
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;">Facility Phone:</td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>{{ $testing->customer->phone }}</center></td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
      </tr>
      <tr style="border: none;">
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;">Test Contractor:</td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>PETRO-TANK SOLUTIONS, LLC</center></td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
      </tr>
      <tr style="border: none;">
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;">Address:</td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>6115 FM 762 Rd Suite 500<br> Richmond, Tx 77469</center></td>
        <td class="text-end" style="padding: 15px 0 0 0; border: none; font-size: 13px;">Tester:</td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>{{ $testing->technician->name }}</center></td>
      </tr>
      <tr style="border: none;">
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px;">Contractor Phone:</td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>(281) 242-2687</center></td>
        <td class="text-end" style="padding: 15px 0 0 0; border: none; font-size: 13px;">License #</td>
        <td style="padding: 15px 0 0 0; border: none; font-size: 13px; border-bottom: 1px solid black;"><center>4865.LTN</center></td>
      </tr>
    </table>

    @php
        $test = json_decode($testing->items, true);
    @endphp

    <table class="table" style="font-size: 12px; margin-top: 0px; line-height: 14px;">
        <tr>
          <td style="font-weight: 600;">Product</td>
          <td style="width: 18%; text-align: center;"><b>{{ $test[0][0] }}</b></td>
          <td style="width: 18%; text-align: center;"><b>{{ $test[0][1] }}</b></td>
          <td style="width: 18%; text-align: center;"><b>{{ $test[0][2] }}</b></td>
          <td style="width: 18%; text-align: center;"><b>{{ $test[0][3] }}</b></td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Pump Manufacturer</td>
          <td style="text-align: center;">{{ $test[1][0] }}</td>
          <td style="text-align: center;">{{ $test[1][1] }}</td>
          <td style="text-align: center;">{{ $test[1][2] }}</td>
          <td style="text-align: center;">{{ $test[1][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Isolation Mechanism</td>
          <td style="text-align: center;">{{ $test[2][0] }}</td>
          <td style="text-align: center;">{{ $test[2][1] }}</td>
          <td style="text-align: center;">{{ $test[2][2] }}</td>
          <td style="text-align: center;">{{ $test[2][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Test Pressure <br><small>(1 1/2 times working pressure)</small></td>
          <td style="text-align: center;">{{ $test[3][0] }}</td>
          <td style="text-align: center;">{{ $test[3][1] }}</td>
          <td style="text-align: center;">{{ $test[3][2] }}</td>
          <td style="text-align: center;">{{ $test[3][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Initial Cylinder Level (ICL)</td>
          <td style="text-align: center;">{{ $test[4][0] }}</td>
          <td style="text-align: center;">{{ $test[4][1] }}</td>
          <td style="text-align: center;">{{ $test[4][2] }}</td>
          <td style="text-align: center;">{{ $test[4][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Final Cylinder Level (FCL)</td>
          <td style="text-align: center;">{{ $test[5][0] }}</td>
          <td style="text-align: center;">{{ $test[5][1] }}</td>
          <td style="text-align: center;">{{ $test[5][2] }}</td>
          <td style="text-align: center;">{{ $test[5][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Leak Volume = ICL - FCL</td>
          <td style="text-align: center;">{{ $test[6][0] }}</td>
          <td style="text-align: center;">{{ $test[6][1] }}</td>
          <td style="text-align: center;">{{ $test[6][2] }}</td>
          <td style="text-align: center;">{{ $test[6][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Time Started</td>
          <td style="text-align: center;">{{ $test[7][0] }}</td>
          <td style="text-align: center;">{{ $test[7][1] }}</td>
          <td style="text-align: center;">{{ $test[7][2] }}</td>
          <td style="text-align: center;">{{ $test[7][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Time Completed</td>
          <td style="text-align: center;">{{ $test[8][0] }}</td>
          <td style="text-align: center;">{{ $test[8][1] }}</td>
          <td style="text-align: center;">{{ $test[8][2] }}</td>
          <td style="text-align: center;">{{ $test[8][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Total Test Time <br><small>(30 min. minimum)</small></td>
          <td style="text-align: center;">{{ $test[9][0] }}</td>
          <td style="text-align: center;">{{ $test[9][1] }}</td>
          <td style="text-align: center;">{{ $test[9][2] }}</td>
          <td style="text-align: center;">{{ $test[9][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Conclusion (Pass or Fail)</td>
          <td style="text-align: center;"><b>{{ $test[10][0] }}</b></td>
          <td style="text-align: center;"><b>{{ $test[10][1] }}</b></td>
          <td style="text-align: center;"><b>{{ $test[10][2] }}</b></td>
          <td style="text-align: center;"><b>{{ $test[10][3] }}</b></td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Leak Detector Manufacturer</td>
          <td style="text-align: center;">{{ $test[11][0] }}</td>
          <td style="text-align: center;">{{ $test[11][1] }}</td>
          <td style="text-align: center;">{{ $test[11][2] }}</td>
          <td style="text-align: center;">{{ $test[11][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Line PSI</td>
          <td style="text-align: center;">{{ $test[12][0] }}</td>
          <td style="text-align: center;">{{ $test[12][1] }}</td>
          <td style="text-align: center;">{{ $test[12][2] }}</td>
          <td style="text-align: center;">{{ $test[12][3] }}</td>
        </tr>
        <tr>
          <td style="font-weight: 600;">Leak Detector Result <br>(Pass or Fail)</td>
          <td style="text-align: center;"><b>{{ $test[13][0] }}</b></td>
          <td style="text-align: center;"><b>{{ $test[13][1] }}</b></td>
          <td style="text-align: center;"><b>{{ $test[13][2] }}</b></td>
          <td style="text-align: center;"><b>{{ $test[13][3] }}</b></td>
        </tr>
    </table>

    <h6 style="font-size: 12px; font-weight: 600;">
      Comments:___________________________________________________________________________________________________________
      <br>_____________________________________________________________________________________________________________________
      <br>_____________________________________________________________________________________________________________________
      <br>_____________________________________________________________________________________________________________________
      <br>_____________________________________________________________________________________________________________________
    </h6>

</body>
</html>