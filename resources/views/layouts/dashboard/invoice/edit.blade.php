@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<style>
    #add-btn-cont {
        width: 50%;
        text-align: center;
        margin: 30px auto;
    }

    .btn-divider {
        width: 100%;
        height: 0;
    }

    #add_item {
        margin-top: -30px;
    }

    .inv-item {
        box-shadow: 0 2px 5px #0000001f;
    }
</style>


<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Edit Invoice <b>»</b> {{ $invoice->invoice_no }}</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('invoice', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <form class="col-md-11 m-auto" id="inv-form" action="{{ url('/dashboard/invoice/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name="inv_id" value="{{ $invoice->id }}">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $invoice->date }}" required>
            </div> 
            <div class="col-md-6">
                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                <select class="form-select" id="customer_id" name="customer_id">
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $customer->id == $invoice->customer_id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->fac_id }})</option>
                    @endforeach
                </select> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="service" class="form-label">Service</label>
                <input type="text" class="form-control" id="service" name="service" value="{{ $invoice->service }}">
            </div>
            <div class="col-md-6">
                <label for="pay_opt" class="form-label">Payment option <span class="text-danger">*</span></label>
                <select class="form-select" id="pay_opt" name="pay_opt">
                    <option value="">-- Select Option --</option>
                    <option value="Money Order" {{ $invoice->pay_opt == 'Money Order' ? 'selected' : '' }}>Money Order</option>
                    <option value="Check" {{ $invoice->pay_opt == 'Check' ? 'selected' : '' }}>Check</option>
                    <option value="Cash" {{ $invoice->pay_opt == 'Cash' ? 'selected' : '' }}>Cash</option>
                </select> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 check_no" style="display: none;">
                <label for="service" class="form-label">Check number</label>
                <input type="text" class="form-control" id="check_no" name="check_no" value="{{ $invoice->check_no }}">
            </div>
            <div class="col-md-6">
                <label for="payment" class="form-label">Payment Status <span class="text-danger">*</span></label>
                <div class="d-flex">
                    <div class="form-check me-4 mt-1">
                        <input class="form-check-input" type="radio" name="payment" id="paid" value="Paid" required {{ $invoice->payment == 'Paid' ? 'checked' : '' }}>
                        <label class="form-check-label" for="paid">Paid</label>
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="radio" name="payment" id="unpaid" value="Unpaid" required {{ !$invoice->payment || $invoice->payment == 'Unpaid' ? 'checked' : '' }}>
                        <label class="form-check-label" for="unpaid">Unpaid</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="service" class="form-label">P.O. #</label>
                <input type="text" class="form-control" id="po_no" name="po_no" value="{{ $invoice->po_no }}">
            </div>
        </div>

        <div class="mt-5 mb-3 pt-2 pb-0 px-4 rounded" style="border: 2px solid #53884047;">
            <h4 class="text-center mb-3">Items</h4>
            <div id="item-cont">
            @if( $invoice->invoice_items->count() )
                @foreach($invoice->invoice_items as $item)

                        <div class="row mb-2 p-2 rounded-1 border border-light-subtle inv-item">
                            <div class="col-md-2">
                                <label class="form-label">Item <span class="text-danger">*</span></label>
                                <select class="form-select item-name" required>
                                    <option value="">-- Select Item --</option>
                                    <option value="Calibration" {{ $item->item_name == 'Calibration' ? 'selected' : '' }}>Calibration</option>
                                    <option value="Cathodic Protection Test" {{ $item->item_name == 'Cathodic Protection Test' ? 'selected' : '' }}>Cathodic Protection Test</option>
                                    <option value="Stage 1 Test" {{ $item->item_name == 'Stage 1 Test' ? 'selected' : '' }}>Stage 1 Test</option>
                                    <option value="Annual Line Test" {{ $item->item_name == 'Annual Line Test' ? 'selected' : '' }}>Annual Line Test</option>
                                    <option value="A+B Certificate Renewal" {{ $item->item_name == 'A+B Certificate Renewal' ? 'selected' : '' }}>A+B Certificate Renewal</option>
                                    <option value="Overfill Prevention Test" {{ $item->item_name == 'Overfill Prevention Test' ? 'selected' : '' }}>Overfill Prevention Test</option>
                                    <option value="Monthly Inspection" {{ $item->item_name == 'Monthly Inspection' ? 'selected' : '' }}>Monthly Inspection</option>
                                    @foreach($inventorys as $inventory)
                                         <option value="{{ $inventory->part_no }}" {{ $item->item_name == $inventory->part_no ? 'selected' : '' }}>{{ $inventory->part_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control descript" placeholder="Description" value="{{ $item->descript }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">QTY</label>
                                <input type="number" class="form-control qty" placeholder="QTY" value="{{ $item->qty ?? 1 }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Rate</label>
                                <input type="number" class="form-control rate" placeholder="Rate" value="{{ $item->rate }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control amount" placeholder="Amount" value="{{ $item->amount }}" required>
                            </div>
                            <div class="col-md-1 d-flex">
                                <button type="button" class="btn-close close-item m-auto" aria-label="Close" disabled></button>
                            </div>
                        </div>

                @endforeach
            @else
                
                    <div class="row mb-2 p-2 rounded-1 border border-light-subtle inv-item">
                        <div class="col-md-2">
                            <label class="form-label">Item <span class="text-danger">*</span></label>
                            <select class="form-select item-name" required>
                                <option value="">-- Select Item --</option>
                                <option value="Calibration">Calibration</option>
                                <option value="Cathodic Protection Test">Cathodic Protection Test</option>
                                <option value="Stage 1 Test">Stage 1 Test</option>
                                <option value="Annual Line Test">Annual Line Test</option>
                                <option value="A+B Certificate Renewal">A+B Certificate Renewal</option>
                                <option value="Overfill Prevention Test">Overfill Prevention Test</option>
                                <option value="Monthly Inspection">Monthly Inspection</option>
                                @foreach($inventorys as $inventory)
                                     <option value="{{ $inventory->part_no }}">{{ $inventory->part_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control descript" placeholder="Description" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">QTY</label>
                            <input type="number" class="form-control qty" placeholder="QTY">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Rate</label>
                            <input type="number" class="form-control rate" placeholder="Rate">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control amount" placeholder="Amount" required>
                        </div>
                        <div class="col-md-1 d-flex">
                            <button type="button" class="btn-close close-item m-auto" aria-label="Close" disabled></button>
                        </div>
                    </div>
                
            @endif
            </div>
            <div class="d-flex justify-content-center" id="add-btn-cont">
                <div class="border border-2 border-success rounded-pill btn-divider">
                    <button type="button" id="add_item" class="btn btn-primary py-1 px-2"  aria-label="Add"><i class="fa fa-plus"></i></button>
                </div>
            </div>

            <input type="hidden" id="invoice_items" name="invoice_items" value="">
        </div>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="addi_comments" class="form-label">Comments</label>
                <textarea class="form-control" id="addi_comments" name="addi_comments">{{ $invoice->addi_comments }}</textarea>
            </div>
        </div>
        
        @php
            $paid_amounts = $invoice->paid_amount ? json_decode($invoice->paid_amount, true) : [];
        @endphp
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="paid_amount" class="form-label">Paid Amount</label>
                <input type="text" class="form-control" id="paid_amount" name="paid_amount" {{ count($paid_amounts) == 1 ? 'disabled value=' . $paid_amounts[0][0] : '' }}>
            </div>
            <div class="col-md-3">
                <label for="paid_date" class="form-label">Paid Date</label>
                <input type="date" class="form-control" id="paid_date" name="paid_date"{{ count($paid_amounts) == 1 ? 'disabled value=' . \Carbon\Carbon::parse($paid_amounts[0][1])->format('Y-m-d') : '' }}>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <button type="button" id="frm_submit" class="btn btn-primary mt-2">Update</button>
            </div>
            <div class="col-md-6">
                <table class="table float-end table-borderless" style="width: 200px">
                    <thead style="display: none;">
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Sub-Total</b></td>
                            <td class="text-end"><b>$<span id="sub-total">0</span></b></td>
                        </tr>
                        <tr>
                            <td><b>Sales Tax</td>
                            <td class="text-end"><b>$<span id="sub-tax">0</span></b></td>
                        </tr>
                        <tr>
                            <td><b>Total Due</td>
                            <td class="text-end"><b>$<span id="total">0</span></b></td>
                        </tr>
                        @php
                            $current_paid = 0;
                            foreach($paid_amounts as $paid_am){
                                $amount = number_format((float)$paid_am[0], 2);
                                $date = \Carbon\Carbon::parse($paid_am[1])->format('n/j/Y');
                                $current_paid += $amount;
                                
                                echo '<tr>
                                        <td class="text-success text-nowrap"><b>Paid Amount (' . $date . ')</td>
                                        <td class="text-end text-success"><b>$' . $amount . '</b></td>
                                    </tr>';
                            }
                        @endphp
                        <tr id="new-paid" style="display: none;">
                            <td class="text-success text-nowrap"><b>Paid Amount <span id="paid-date"></span></td>
                            <td class="text-end text-success"><b>$<span id="total-paid">0</span></b></td>
                        </tr>
                        <tr>
                            <td><b>Balance Due</td>
                            <td class="text-end"><b>$<span id="balance-due">0</span></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>


<script>

    var customer_id = document.querySelector('#customer_id');

    dselect(customer_id, {
        search: true
    });


    function update_total(){
        var sub_total = 0;

        $('.amount').each(function() {
            sub_total += +$(this).val(); 
        });
        
        var sub_tax = sub_total * {{ config('app.sales_tax_percentage') }};
        var total_due = (sub_total + sub_tax).toFixed(2);
        
        var paid_date = new Date($('#paid_date').val());
        paid_date = $('#paid_date').val() ? '(' + paid_date.toLocaleDateString('en-US') + ')' : '';
        
        var current_paid = {{ $current_paid }};
        var total_paid = 0;
        
        if(!$('#paid_amount').prop('disabled') && $('#paid_amount').val()){
            $('#new-paid').show();
            var total_paid = (+$('#paid_amount').val()).toFixed(2);
        } else {
            if(+$('#paid_amount').val() < total_due){
                $('#paid_amount, #paid_date').val('').prop('disabled', false);
            }
        }
        
        $('#sub-total').text(sub_total.toFixed(2));
        $('#sub-tax').text(sub_tax.toFixed(2));
        $('#total').text(total_due);
        $('#total-paid').html(total_paid);
        $('#paid-date').text(paid_date);
        $('#balance-due').text((total_due - total_paid - current_paid).toFixed(2));
        
    }
    update_total();
    $(document).on('change', '.amount, #paid_amount, #paid_date', function(){
        update_total();
    });
    
    function payOpt_changed(){
        if($('#pay_opt').val() == 'Check'){
            $('.check_no').show();
            $('#check_no').prop('disabled', false);
        } else {
            $('.check_no').hide();
            $('#check_no').prop('disabled', true);
        }
        
        if($('#pay_opt').val() != ''){
            $('#paid').prop('checked', true);
        } else {
            $('#unpaid').prop('checked', true);
        }
    }
    payOpt_changed();

    $('#pay_opt').change( payOpt_changed );
    
    
    var itemData = {
        'Calibration': {
            'description': 'Ensuring equipment accuracy and operational efficiency',
            'rate': 125
        },
        'Cathodic Protection Test': {
            'description': 'Test for preventing corrosion of metal surfaces',
            'rate': 450
        },
        'Stage 1 Test': {
            'description': 'Test to check vapor recovery systems',
            'rate': 300
        },
        'Annual Line Test': {
            'description': 'Yearly test for pipeline integrity',
            'rate': 100
        },
        'A+B Certificate Renewal': {
            'description': 'Certification for environmental compliance',
            'rate': 300
        },
        'Overfill Prevention Test': {
            'description': 'Test to prevent fuel overfilling',
            'rate': 150
        },
        'Monthly Inspection': {
            'description': 'Regular monthly inspection for system maintenance',
            'rate': 125
        }
    };
    
    @foreach($inventorys as $inventory)
        itemData['{{ $inventory->part_no }}'] = {
            'description': '{{ $inventory->serial }}',
            'rate': {{ $inventory->selling_price }}
        };
    @endforeach

    $(document).on('change', '.item-name', function() {
        var selectedItem = $(this).val();
        var inv_item = $(this).closest('.inv-item');

        if (selectedItem && itemData[selectedItem]) {
            $(inv_item).find('.descript').val(itemData[selectedItem].description);
            $(inv_item).find('.rate').val(itemData[selectedItem].rate);
        } else {
            $(inv_item).find('.descript').val('');
            $(inv_item).find('.rate').val('');
        }
        
        update_total();
    });


    $('#add_item').on('click', function() {
        var new_item = $(`<div class="row mb-2 p-2 rounded-1 border border-light-subtle inv-item">
                <div class="col-md-2">
                    <label class="form-label">Item <span class="text-danger">*</span></label>
                    <select class="form-select item-name" required>
                        <option value="">-- Select Item --</option>
                        <option value="Calibration">Calibration</option>
                        <option value="Cathodic Protection Test">Cathodic Protection Test</option>
                        <option value="Stage 1 Test">Stage 1 Test</option>
                        <option value="Annual Line Test">Annual Line Test</option>
                        <option value="A+B Certificate Renewal">A+B Certificate Renewal</option>
                        <option value="Overfill Prevention Test">Overfill Prevention Test</option>
                        <option value="Monthly Inspection">Monthly Inspection</option>
                        @foreach($inventorys as $inventory)
                            <option value="{{ $inventory->part_no }}">{{ $inventory->part_no }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" class="form-control descript" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">QTY</label>
                    <input type="number" class="form-control qty" placeholder="QTY">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Rate</label>
                    <input type="number" class="form-control rate" placeholder="Rate">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                    <input type="number" class="form-control amount" placeholder="Amount" required>
                </div>
                <div class="col-md-1 d-flex">
                    <button type="button" class="btn-close close-item m-auto" aria-label="Close" disabled></button>
                </div>
            </div>`);

        new_item.appendTo('#item-cont');
        $('.inv-item .close-item').prop('disabled', false);
    });

    $(document).on('click', '.close-item', function() {
        if($('body .close-item').length !== 1){
            this.closest('.inv-item').remove();
        }
    });
    
    $(document).on('change', '.qty, .rate', function() {
        var parent_el = $(this).closest('.inv-item');
        var qty = $(parent_el).find('.qty').val();
        var rate = $(parent_el).find('.rate').val();
        
        if($.isNumeric(qty) && $.isNumeric(rate)){
            var amount = rate * qty;
            $(parent_el).find('.amount').val(amount);
            update_total();
        } else {
            $(parent_el).find('.amount').val('');
        }
    });

    $('#frm_submit').on('click', function(e) {

        event.preventDefault(e);

        var form = $('#inv-form')[0];

        if (form.checkValidity() === false) {
            form.reportValidity();
            return;
        }
        
        var invoice_items = [];

        $('.inv-item').each(function(index) {
            var $inv_item = $(this);
            var item_name = $inv_item.find('.item-name').val();
            var descript = $inv_item.find('.descript').val();
            var qty = $inv_item.find('.qty').val();
            var rate = $inv_item.find('.rate').val();
            var amount = $inv_item.find('.amount').val();

            var dataSet = {
                item_name: item_name,
                descript: descript,
                qty: qty,
                rate: rate,
                amount: amount,
            };

            invoice_items.push(dataSet);
        });

        $('#invoice_items').val( JSON.stringify(invoice_items) );
        $('#inv-form').submit();
    });

</script>