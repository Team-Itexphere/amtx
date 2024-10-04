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
            font-size: 15px;
            font-weight: 600;
            line-height: 10px;
        }
        
        .no-bd {
            border: none;
        }
        
        .font-12 {
            font-size: 12px !important;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <h5 class="main-hd"><center><b><u>MONTHLY INSPECTION REPORT</u></b></center></h5>
    <table class="no-bd">
      <td style="width: 70mm;">
          <b class="font-12">
            @if($testing->customer->com_to_inv == 'AMTS')
                <span style="color: #FFC000;">AMTS</span>
            @else
                <span style="color: #00B0F0;">PETRO</span>-<span style="color: #FFC000;">TANK</span> <span style="color: #7F7F7F;">SOLUTIONS</span>
            @endif
          </b><br>
          <strong>
              <p style="font-size: 10px; margin-top: 0; line-height: 14px;">
                  803 SUMMER PARK DR, STE #150<br>
                  STAFFORD, TX 77477<br>
                  O: 281-242-2687<br>
                  E: OFFICE.PETROTANKSOLUTIONS@GMAIL.COM<br>
              </p>
          </strong>
      </td>
      <td style="text-align: right; width: 115mm;">
          <img src="{{ $testing->customer->com_to_inv == 'AMTS' ? './img/amts-logo.jpg' : './img/pts-logo.png' }}" height="65">
      </td>
    </table>
  
    <table class="no-bd" style="font-size: 10px; margin-top: 0; line-height: 14px;">
      <tr style="background: black; transparent: black;">
        <td colspan="3">-</td>
      </tr>
      <tr style="background: #caedfb;">
        <td style="width: 60mm;"><strong>Facility Name:</strong> <u>{{ $testing->customer->name }}</u></td>
        <td style="text-align: center; width: 82mm;"><strong>Facility Address:</strong> <u>{{ $testing->customer->str_addr }}</u></td>
        <td style="text-align: right; width: 41mm;"><strong>TCEQ Facility ID:</strong> <u>{{ $testing->customer->fac_id }}</u></td>
      </tr>
      <tr style="background: #caedfb; color: transparent;">
        <td colspan="3">-</td>
      </tr>
      <tr style="background: #caedfb;">
        <td style="width: 60.5mm;"><strong>Technician:</strong> <u>{{ $testing->technician->name }}</u></td>
        <td style="text-align: center;"><strong>Time:</strong> <u>{{ $testing->updated_at->format('h:s A') }}</u></td>
        <td style="text-align: right;"><strong>Date Inspected:</strong> <u>{{ $testing->updated_at->format('m/d/Y') }}</u></td>
      </tr>
      <tr style="background: #caedfb; color: transparent;">
        <td colspan="3">-</td>
      </tr>
    </table>

  <div class="main-cont">

    <div class="ques-cont">
      <table style="width:100%">
          <tbody>
            <tr>
              <td style="background: #caedfb;"></td>
              <td class="orange" colspan="4"><strong>NOT APPLICABLE</strong></td>
            </tr>
            <tr>
              <td style="background: #caedfb;"></td>
              <td class="orange"></td>
              <td class="red" colspan="3"><strong>REQUIRES IMMEDIATE ATTENTION</strong></td>
            </tr>
            <tr>
              <td style="background: #caedfb;"></td>
              <td class="orange"></td>
              <td class="red"></td>
              <td class="green" colspan="2"><strong>CHECKED AND OKAY</strong></td>
            </tr>
            <tr>
              <td style="background: black;"></td>
              <td class="orange" style="width: 10mm;"></td>
              <td class="red" style="width: 10mm;"></td>
              <td class="green" style="width: 10mm;"></td>
              <td style="background: black; color: white;"><strong>COMMENTS</strong></td>
            </tr>

            @for ($i = 0; $i < 19; $i++)
              @if (isset($questions[$i]))
                <tr>
                  <td><b>{{ $questions[$i]['id'] }} {{ $questions[$i]['question'] }}</b>
                    @if (isset($answers[$i]['file']))
                      <div class="text-center file-item">
                        <img src="./{{ $answers[$i]['file'] }}">
                      </div>
                    @endif
                  </td>
                  <td class="align-top text-center orange"><input type="checkbox" {{ $answers[$i]['answer'] == 'N/A' ? 'checked' : '' }}></td>
                  <td class="align-top text-center red"><input type="checkbox" {{ $answers[$i]['answer'] == 'No' ? 'checked' : '' }}></td>
                  <td class="align-top text-center green"><input type="checkbox" {{ $answers[$i]['answer'] == 'Yes' ? 'checked' : '' }}></td>
                  <td class="align-top">{{ $answers[$i]['desc'] }}</td>
                </tr>
                <tr>
                  <td style="color: transparent;">-</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              @endif
            @endfor
          </tbody>
      </table>
    </div>

    <p style="font-size: 10px;">
      <b>GENERAL COMMENTS:</b>_________________________________________________________________________________________________________
      <br>_____________________________________________________________________________________________________________________________
      <br>_____________________________________________________________________________________________________________________________
      <br>_____________________________________________________________________________________________________________________________
      <br>_____________________________________________________________________________________________________________________________
    </p>

  </div>

</body>
</html>