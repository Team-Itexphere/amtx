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
            font-size: 20px;
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
    </style>
</head>
<body>
    <div style="width: 100%; text-align: center;"><img src="./img/pts-logo.png" height="85"></div>
    <h5 class="main-hd"><center>LINE AND LEAK DETECTOR TEST DATA FORM</center></h5>
    
    <table class="table" style="font-size: 13px; margin-top: -3px; font-weight: 600;">
      <tr style="border: none;">
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px; ">Facility Name:</td>
        <td style="width: 40%; padding: 15px 0 0 0; border: none; font-size: 13px; ">___________________________________</td>
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;">Date:</td>
        <td style="width: 30%; padding: 15px 0 0 0; border: none; font-size: 13px;">____________________________________</td>
      </tr>
      <tr style="border: none;">
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;">Facility Address:</td>
        <td style="width: 40%; padding: 15px 0 0 0; border: none; font-size: 13px; ">____________________________________</td>
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
        <td style="width: 30%; padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
      </tr>
      <tr style="border: none;">
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;">Facility Phone:</td>
        <td style="width: 40%; padding: 15px 0 0 0; border: none; font-size: 13px; ">____________________________________</td>
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
        <td style="width: 30%; padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
      </tr>
      <tr style="border: none;">
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;">Test Contractor:</td>
        <td style="width: 40%; padding: 15px 0 0 0; border: none; font-size: 13px; ">____________________________________</td>
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
        <td style="width: 30%; padding: 15px 0 0 0; border: none; font-size: 13px;"></td>
      </tr>
      <tr style="border: none;">
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;">Address:</td>
        <td style="width: 40%; padding: 15px 0 0 0; border: none; font-size: 13px; ">____________________________________</td>
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;">Tester:</td>
        <td style="width: 30%; padding: 15px 0 0 0; border: none; font-size: 13px;">____________________________________</td>
      </tr>
      <tr style="border: none;">
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;">Contractor Phone:</td>
        <td style="width: 40%; padding: 15px 0 0 0; border: none; font-size: 13px; ">____________________________________</td>
        <td style="width: 15%; padding: 15px 0 0 0; border: none; font-size: 13px;">License #</td>
        <td style="width: 30%; padding: 15px 0 0 0; border: none; font-size: 13px;">____________________________________</td>
      </tr>
    </table>

    <table class="table" style="font-size: 12px; margin-top: 0px; line-height: 14px;">
        <tr>
          <td style="font-weight: 600;">Product</td>
          <td style="width: 18%; text-align: center; font-weight: 600;">REGULAR</td>
          <td style="width: 18%; text-align: center; font-weight: 600;">SUPER</td>
          <td style="width: 18%; text-align: center; font-weight: 600;">DISESL</td>
          <td style="width: 18%; text-align: center; font-weight: 600;"></td>
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