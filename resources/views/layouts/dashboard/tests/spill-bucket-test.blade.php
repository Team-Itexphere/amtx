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
        
        .main-hd {
            font-size: 67%;
            font-weight: 400;
            border-bottom: 1px solid black;
        }

        .table th, .table td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 2px 4px;
        }
    </style>
</head>
<body>
    <h4 style="font-weight: 700; background: black; color: white; padding: 4px; margin-top: 12px;"><center>APPENDIX C-3</center></h4>
    
    <table class="table" style="font-size: 11px; margin-top: -3px; line-height: 14px;">
      <tr>
        <td colspan="4" style="font-size: 13px; background: black; color: white; padding: 7px; font-weight: 700;"><center>SPILL BUCKET INTEGRITY TESTING HYDROSTATIC TEST METHOD <br>SINGLE AND DOUBLE-WALLED VACCUM TEST METHOD</center></td>
      </tr>
      <tr>
        <td colspan="2" style="width: 45%;">Facility Name: {{ $testing->customer->name }}</td>
        <td colspan="2" style="width: 55%;">Owner: {{ $testing->customer->own_name }}</td>
      </tr>
      <tr>
        <td colspan="2" style="width: 45%;">Address: {{ $testing->customer->str_addr }}</td>
        <td colspan="2" style="width: 55%;">Address: {{ $testing->customer->str_addr }}</td>
      </tr>
      <tr>
        <td colspan="2" style="width: 45%;">City, State, Zip Code:</td>
        <td colspan="2" style="width: 55%;">City, State, Zip Code:
      </td>
      </tr>
      <tr>
        <td colspan="2" style="width: 45%;">Facility I.D. #: {{ $testing->customer->fac_id }}</td>
        <td colspan="2" style="width: 55%;">Phone #: {{ $testing->customer->phone }}</td>
      </tr>
      <tr>
        <td colspan="2" style="width: 45%;">Testing Company: {{ $testing->customer->com_to_inv }}</td>
        <td style="width: 27.5%;">Phone #:</td>
        <td style="width: 27.5%;">Date: {{ \Carbon\Carbon::parse($testing->date)->format('m/d/Y') }}</td>
      </tr>
      <tr>
        <td colspan="4">This procedure is to test the leak integrity of single- and double-walled spill buckets. See PEI/RP1200 Section 6.2 for hydrostatic test method, Section 6.3 for single-walled vacuum test method and Section 6.4 for double-walled vacuum test method.</td>
      </tr>
    </table>

    @php
        $test = json_decode($testing->items, true);
    @endphp

    <table class="table" style="font-size: 11px; margin-top: -17px; line-height: 14px;">
        <tr>
          <td style="width: 15%;">Tank Number</td>
          <td style="width: 15%; text-align: center;">{{ $test[0][0] }}</td>
          <td style="width: 15%; text-align: center;">{{ $test[0][1] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[0][2] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[0][3] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[0][4] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[0][5] }}</td>
        </tr>
        <tr>
          <td>Product Stored</td>
          <td style="text-align: center;">{{ $test[1][0] }}</td>
          <td style="text-align: center;">{{ $test[1][1] }}</td>
          <td style="text-align: center;">{{ $test[1][2] }}</td>
          <td style="text-align: center;">{{ $test[1][3] }}</td>
          <td style="text-align: center;">{{ $test[1][4] }}</td>
          <td style="text-align: center;">{{ $test[1][5] }}</td>
        </tr>
        <tr>
          <td>Spill Bucket Capacity</td>
          <td style="text-align: center;">{{ $test[2][0] }}</td>
          <td style="text-align: center;">{{ $test[2][1] }}</td>
          <td style="text-align: center;">{{ $test[2][2] }}</td>
          <td style="text-align: center;">{{ $test[2][3] }}</td>
          <td style="text-align: center;">{{ $test[2][4] }}</td>
          <td style="text-align: center;">{{ $test[2][5] }}</td>
        </tr>
        <tr>
          <td>Manufacturer</td>
          <td style="text-align: center;">{{ $test[3][0] }}</td>
          <td style="text-align: center;">{{ $test[3][1] }}</td>
          <td style="text-align: center;">{{ $test[3][2] }}</td>
          <td style="text-align: center;">{{ $test[3][3] }}</td>
          <td style="text-align: center;">{{ $test[3][4] }}</td>
          <td style="text-align: center;">{{ $test[3][5] }}</td>
        </tr>

        <tr>
          <td>Construction</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][0] == 'Yes' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][0] == 'No' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][1] == 'Yes' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][1] == 'No' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][2] == 'Yes' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][2] == 'No' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][3] == 'Yes' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][3] == 'No' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][4] == 'Yes' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][4] == 'No' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[4][5] == 'Yes' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[4][5] == 'No' ? 'checked' : '' }}> Double-walled</td>
        </tr>
        <tr>
          <td>Test Type</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][0] == 'HY' ? 'checked' : '' }}> Hydrostatic <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][0] == 'VA' ? 'checked' : '' }}> Vacuum <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][0] == 'SI' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][0] == 'DO' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][1] == 'HY' ? 'checked' : '' }}> Hydrostatic <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][1] == 'VA' ? 'checked' : '' }}> Vacuum <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][1] == 'SI' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][1] == 'DO' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][2] == 'HY' ? 'checked' : '' }}> Hydrostatic <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][2] == 'VA' ? 'checked' : '' }}> Vacuum <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][2] == 'SI' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][2] == 'DO' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][3] == 'HY' ? 'checked' : '' }}> Hydrostatic <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][3] == 'VA' ? 'checked' : '' }}> Vacuum <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][3] == 'SI' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][3] == 'DO' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][4] == 'HY' ? 'checked' : '' }}> Hydrostatic <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][4] == 'VA' ? 'checked' : '' }}> Vacuum <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][4] == 'SI' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][4] == 'DO' ? 'checked' : '' }}> Double-walled</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[5][5] == 'HY' ? 'checked' : '' }}> Hydrostatic <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][5] == 'VA' ? 'checked' : '' }}> Vacuum <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][5] == 'SI' ? 'checked' : '' }}> Single-walled <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[5][5] == 'DO' ? 'checked' : '' }}> Double-walled</td>
        </tr>
        <tr>
          <td>Spill Bucket Type</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][0] == 'Yes' ? 'checked' : '' }}> Product <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][0] == 'No' ? 'checked' : '' }}> Vapor</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][1] == 'Yes' ? 'checked' : '' }}> Product <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][1] == 'No' ? 'checked' : '' }}> Vapor</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][2] == 'Yes' ? 'checked' : '' }}> Product <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][2] == 'No' ? 'checked' : '' }}> Vapor</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][3] == 'Yes' ? 'checked' : '' }}> Product <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][3] == 'No' ? 'checked' : '' }}> Vapor</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][4] == 'Yes' ? 'checked' : '' }}> Product <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][4] == 'No' ? 'checked' : '' }}> Vapor</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[6][5] == 'Yes' ? 'checked' : '' }}> Product <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[6][5] == 'No' ? 'checked' : '' }}> Vapor</td>
        </tr>
        <tr>
          <td>Liquid and debris removed from spill bucket?*</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[7][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[7][5] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td>Visual Inspection (No cracks, loose parts or separation of the bucket from the fill pipe.)</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][0] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][1] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][2] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][3] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][4] == 'No' ? 'checked' : '' }}> No</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[8][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[8][5] == 'No' ? 'checked' : '' }}> No</td>
        </tr>
        <tr>
          <td>Tank riser cap included in test?</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][0] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][0] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][1] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][1] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][2] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][2] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][3] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][3] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][4] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][4] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[9][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][5] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[9][5] == 'NA' ? 'checked' : '' }}> NA</td>
        </tr>
        <tr>
          <td>Drain valve included in test?</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[10][0] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][0] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][0] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[10][1] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][1] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][1] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[10][2] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][2] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][2] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[10][3] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][3] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][3] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[10][4] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][4] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][4] == 'NA' ? 'checked' : '' }}> NA</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[10][5] == 'Yes' ? 'checked' : '' }}> Yes &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][5] == 'No' ? 'checked' : '' }}> No <br><input style="margin-bottom: -3px;" type="checkbox" {{ $test[10][5] == 'NA' ? 'checked' : '' }}> NA</td>
        </tr>

        <tr>
          <td style="width: 15%;">Starting Level</td>
          <td style="width: 15%; text-align: center;">{{ $test[11][0] }}</td>
          <td style="width: 15%; text-align: center;">{{ $test[11][1] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[11][2] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[11][3] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[11][4] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[11][5] }}</td>
        </tr>
        <tr>
          <td style="width: 15%;">Test Start Time</td>
          <td style="width: 15%; text-align: center;">{{ $test[12][0] }}</td>
          <td style="width: 15%; text-align: center;">{{ $test[12][1] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[12][2] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[12][3] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[12][4] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[12][5] }}</td>
        </tr>
        <tr>
          <td style="width: 15%;">Ending Level</td>
          <td style="width: 15%; text-align: center;">{{ $test[13][0] }}</td>
          <td style="width: 15%; text-align: center;">{{ $test[13][1] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[13][2] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[13][3] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[13][4] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[13][5] }}</td>
        </tr>
        <tr>
          <td style="width: 15%;">Test End Time</td>
          <td style="width: 15%; text-align: center;">{{ $test[14][0] }}</td>
          <td style="width: 15%; text-align: center;">{{ $test[14][1] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[14][2] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[14][3] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[14][4] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[14][5] }}</td>
        </tr>
        <tr>
          <td style="width: 15%;">Test Period</td>
          <td style="width: 15%; text-align: center;">{{ $test[15][0] }}</td>
          <td style="width: 15%; text-align: center;">{{ $test[15][1] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[15][2] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[15][3] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[15][4] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[15][5] }}</td>
        </tr>
        <tr>
          <td style="width: 15%;">Level Change</td>
          <td style="width: 15%; text-align: center;">{{ $test[16][0] }}</td>
          <td style="width: 15%; text-align: center;">{{ $test[16][1] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[16][2] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[16][3] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[16][4] }}</td>
          <td style="width: 13.75%; text-align: center;">{{ $test[16][5] }}</td>
        </tr>

        <tr>
          <td colspan="7">Pass/fail criteria: Must pass visual inspection. Hydrostatic: Water level drop of less than 1/8 inch; Vacuum single-walled only: <br>Maintain at least 26 inches water column; Vacuum double-walled: maintain at least 12 inches water column.</td>
        </tr>
        <tr style="font-weight: 700; background: #d9d9d9;">
          <td>Test Results</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[17][0] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[17][0] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[17][1] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[17][1] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[17][2] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[17][2] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[17][3] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[17][3] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[17][4] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[17][4] == 'Fail' ? 'checked' : '' }}> Fail</td>
          <td class="align-middle" style="text-align: center; font-size: 9px;"><input type="checkbox" style="margin-bottom: -3px;" {{ $test[17][5] == 'Pass' ? 'checked' : '' }}> Pass &nbsp; <input style="margin-bottom: -3px;" type="checkbox" {{ $test[17][5] == 'Fail' ? 'checked' : '' }}> Fail</td>
        </tr>
        <tr>
          <td colspan="7" style="font-weight: 700;">Comments <br><br><br><br></td>
        </tr>
        <tr style="border: none;">
          <td colspan="7" style="border: none; padding-top: 10px; padding-bottom:">*All liquids and debris must be disposed of properly.</td>
        </tr>
        <tr style="border: none;">
          <td colspan="3" style="padding: 15px 0 0 0; border: none; font-size: 13px;">Tester’s Name (print) <u>{{ $testing->technician->name }}</u></td>
          <td colspan="4" style="padding: 15px 0 0 0; text-align: right; border: none; font-size: 13px;">Tester’s Signature _________________________</td>
        </tr>
    </table>

</body>
</html>