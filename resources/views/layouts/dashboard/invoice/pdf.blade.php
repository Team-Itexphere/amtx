<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        th, td {
            border: 1px solid black;
            padding: 3px;
            height: 25px;
            font-size: 13px;
        }

        body {
          width: 185mm;
          height: 297mm;
        }

        .thick-yb {
          border-left: 2px solid black;
          border-right: 2px solid black;
        }

        .bb-gray {
          border-bottom: 1px solid #dcdcdc;
        }

        .border-white {
          border-left: 2px solid white;
        }

        .l-b-white {
          border-left: 2px solid white;
          border-bottom: 2px solid white;
        }
        
        .meta-wrap {
            width: 255px;
        }
        .meta-lbl {
            width: 100px;
            text-align: right;
            margin-right: 5px;
            color: #FFC000;
            padding-right: 5px !important;
        }
        .meta-val {
            width: 150px;
            text-align: left;
        }
        
        table.one-col *, table#meta *{ 
            border: none;
            font-size: 15px;
            padding: 0;
            font-weight: 700;
            line-height: 22px;
        }
    </style>
</head>
<body>
  <div class="d-flex text-center justify-content-between">
      <div style="float: left; width:70%; text-align: left; margin-top: -10px;">
        <img src="{{ $invoice->customer->com_to_inv == 'AMTS' ? './img/amts-logo.jpg' : './img/pts-logo.png' }}" height="90">
        <table class="one-col" width="255">
            <tr>
                <td>
                    803 Summer Park Drive, Suite 150<br>
                    Stafford, TX 77477<br>
                    (832) 276 - 6144<br>
                    Office.PetroTankSolutions@gmail.com <br>
                    <br>
                    <span style="font-size: 17px; font-weight: 700;">Reason for Service: {{ $invoice->service }}</span><br>
                    <span style="font-size: 17px; font-weight: 700;">Technician Name: {{ Auth::user()->role == 5 ? Auth::user()->name : $invoice->route_list?->technicians()->orderBy('id', 'desc')->pluck('name')->implode(', ') }}</span><br>
                    <span style="font-weight: 600;">Method of Payment: {{ $invoice->pay_opt }} {{ $invoice->pay_opt == 'Check' ? $invoice->check_no : ($invoice->pay_opt == 'MO' ? $invoice->mo_no : '') }}</span>
                </td>
            </tr>
        </table>
      </div>
      <div>
      <div style="float: right; width:30%;">
        <h1 style="font-size: 40px; font-weight: 700; margin-top: 10px; margin-bottom: 20px;">INVOICE</h1>
        <table id="meta" width="255" style="margin-left: -120px;">
            <tr>
                <td class="meta-lbl">DATE:</td>
                <td class="meta-val">{{ \Carbon\Carbon::parse($invoice->date)->format('m/d/Y') }}</td>
            </tr>
            <tr>
                <td class="meta-lbl">INVOICE #:</td>
                <td class="meta-val">{{ $invoice->invoice_no }}</td>
            </tr>
            <tr>
                <td class="meta-lbl">BILL TO:</td>
                <td class="meta-val">{{ $invoice->customer->name }}</td>
            </tr>
            <tr>
                <td class="meta-lbl align-top">ADDRESS:</td>
                <td class="meta-val align-top">
                    {{--@php
                        $address = $invoice->customer->str_addr;
                        $lastComma = strrpos($address, ',');
                        $secondLastComma = strrpos(substr($address, 0, $lastComma), ',');
                    
                        if ($secondLastComma !== false) {
                            $line1 = substr($address, 0, $secondLastComma);
                            $line2 = substr($address, $secondLastComma + 1);
                        } else {
                            $line1 = $address;
                            $line2 = null;
                        }
                    @endphp
                    
                    <span>{{ $line1 }}</span><br>
                    @if ($line2)
                        <span>{{ $line2 }}</span>
                    @endif--}}
                    
                    {{ $invoice->customer->str_addr }}<br>
                    {{ implode(', ', array_filter([$invoice->customer->city, $invoice->customer->state])) . ' ' . $invoice->customer->zip_code }}
                </td>
            </tr>
            <tr>
                <td class="meta-lbl">PHONE:</td>
                <td class="meta-val">{{ $invoice->customer->phone }}</td>
            </tr>
            <tr>
                <td class="meta-lbl">FACILITY ID:</td>
                <td class="meta-val">{{ $invoice->customer->fac_id }}</td>
            </tr>
        </table>
      </div>
    </div>
  </div>
  </br>
  </br>
  </br>
  <br>
  
  <div style="margin-top: 210px;">
    <table style="width: 100%;">
        <thead>
            <tr style="background: #CAEEFB;">
                <th style="width: 37%">Description</th>
                <th style="width: 18%">Category</th>
                <th style="width: 5%">QTY</th>
                <th style="width: 10%">Rate</th>
                <th style="width: 30%">Amount</th>
            </tr>
        </thead>
        <tbody>
          @php
            $subtotal = 0;
            $insp_amount = 0;

            for ($i = 0; $i < 7; $i++) {
                $border_clr = $i != 6 ? 'bb-gray' : '';
                
                if ($i < $invoice->invoice_items->count()) {
                    $item = $invoice->invoice_items[$i];
                    $rate_sign = $item->rate || $item->amount ? '$' : '';
                    $amount_sign = $item->amount ? '$' : '';
                    
                    echo '<tr>
                            <td class="align-middle '. $border_clr .'">' . $item->descript . ($item->location ? ' - ' . $item->location : '') . '</td>
                            <td class="align-middle '. $border_clr .'">' . $item->category . '</td>
                            <td class="align-middle text-center '. $border_clr .'">' . ($item->qty ?? 1) . '</td>
                            <td class="align-middle text-end '. $border_clr .'"><span style="float: left; margin-top: 5px;">' . $rate_sign . '</span>' . number_format(($item->rate ?? $item->amount), 2) . '</td>
                            <td class="align-middle text-end '. $border_clr .'"><span style="float: left; margin-top: 5px;">' . $amount_sign . '</span>' . number_format($item->amount, 2) . '</td>
                        </tr>';
                        
                    $subtotal += $item->amount;
                    
                    if($item->category == 'Monthly Inspection' || $item->category == 'Calibration'){
                        $insp_amount += $item->amount;
                    }
                } else {
                    echo '<tr>
                            <td class="align-middle '. $border_clr .'"></td>
                            <td class="align-middle text-end '. $border_clr .'"></td>
                            <td class="align-middle text-center '. $border_clr .'"></td>
                            <td class="align-middle text-end '. $border_clr .'"></td>
                            <td class="align-middle text-end '. $border_clr .'"></td>
                        </tr>';
                }
            }
            
            $sales_tax = ($subtotal - $insp_amount) * config('app.sales_tax_percentage');
            $sales_tax = round($sales_tax, 2);
            $grand_total = $sales_tax + $subtotal;
            $grand_total = round($grand_total, 2);
            
            $paid_amount = 0;
            $paid_amount_arr = $invoice->paid_amount && is_array($invoice->paid_amount) ? $invoice->paid_amount : [];
            if(count($paid_amount_arr) > 0){
                foreach($paid_amount_arr as $paid){
                    $paid_amount += (float)$paid[0];
                }
            } elseif($invoice->payment == 'Paid') {
                $paid_amount = $grand_total;
            }
            $paid_amount = round($paid_amount, 2);
            
            $balance_due = $grand_total - $paid_amount;
            $balance_due = round($balance_due, 2);
          @endphp
          <tr class="border-white">
            <td class="align-middle" rowspan="3" style="border-bottom: 2px solid white; font-size: 11px; z-index: 99999999;">
                Make all checks payable to Petro-Tank Solutions.<br>
                If you have any questions concerning this invoice<br> 
                contact Anil Momin at (832) 276-6144 or email us at<br>
                Office.PetroTankSolutions@gmail.com.<br>
                If mailing in the payment, mail it to the above address.<br>
            </td>
            <td class="align-middle text-end" colspan="3" style="color: #595959;"><b>SUBTOTAL</b></td>
            <td class="align-middle text-end"><span style="float: left; margin-top: 10px;">$</span>{{ number_format($subtotal, 2) }}</td>
          </tr>
          <tr class="border-white text-end">
            <td class="align-middle" colspan="3" style="color: #595959;"><b>TAX RATE</b></td>
            <td class="align-middle">{{ config('app.sales_tax_percentage') * 100 }}%</td>
          </tr>
          <tr class="border-white text-end">
            <td class="align-middle" colspan="3" style="color: #595959;"><b>SALES TAX</b></td>
            <td class="align-middle" style="border-bottom: 1px solid black;"><span style="float: left; margin-top: 10px;">$</span>{{ number_format($sales_tax, 2) }}</td>
            </tr>
          <tr class="border-white text-end">
            <td class="align-middle l-b-white"></td> 
            <td class="align-middle" style="background: #CAEEFB; color: #595959;" colspan="3"><b>TOTAL DUE</b></td>
            <td class="align-middle" style="background: #CAEEFB;"><span style="float: left; margin-top: 5px;"><b>$</b></span>{{ number_format($grand_total, 2) }}</td>
          </tr>
          <tr class="border-white text-end">
            <td class="align-middle l-b-white"></td> 
            <td class="align-middle" style="background: #CAEEFB; color: #595959;" colspan="3"><b>PAID AMOUNT</b></td>
            <td class="align-middle" style="background: #CAEEFB;"><span style="float: left; margin-top: 5px;"><b>$</b></span>{{ number_format($paid_amount, 2) }}</td>
          </tr>
          <tr class="border-white text-end">
            <td class="align-middle l-b-white"></td> 
            <td class="align-middle" style="background: #CAEEFB; color: #595959;" colspan="3">
                <b>BALANCE DUE</b>
                @if($invoice->payment == 'Paid')
                    <img src="{{ url('img/paid-seal.png') }}" height="120" style="position: relative; margin-top: -120px; right: 100px;" />
                @endif
            </td>
            <td class="align-middle" style="background: #CAEEFB;"><span style="float: left; margin-top: 5px;"><b>$</b></span>{{ number_format($balance_due, 2) }}</td>
          </tr>
          <tr class="border-white text-end">
            <td class="align-middle l-b-white text-start"><span style="font-weight: 600;">THANK YOU FOR YOUR BUSINESS!</span></td> 
            <td class="align-middle" style="background: #FFC000;" colspan="3"><b>Customer Signature:</b></td>
            <td class="align-middle text-center">
                @if($invoice->signature)
                    <img src="{{ url('/') . $invoice->signature }}" height="30" />
                @endif
            </td>
          </tr>
        </tbody>
    </table>
  </div>
  
</br>
<p style="margin-bottom: 30px;"><b><span style="font-weight: 600;">COMMENTS:</span></b></p>
@if($invoice->addi_comments)
    <p style="width: 100%; border-bottom: 1px solid black;">{{ $invoice->addi_comments }}</p>
@else
    <p style="width: 100%; border-bottom: 1px solid black; height: 12px;"></p>
    <p style="width: 100%; border-bottom: 1px solid black; height: 12px;"></p>
@endif

<p class="mt-4 text-center" style="color: #FFC000; font-size: 13px;"><b>TESTING • COMPLIANCE • MAINTENANCE • PARTS • NEW FUEL CONSTRUCTION • NEW FUEL CANOPY</b></p>

</body>
</html>