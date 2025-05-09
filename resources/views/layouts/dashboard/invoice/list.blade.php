<style>
.popovr {
    cursor: pointer;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Invoices 
            @if($fac_id)
                <b>»</b> {{ $fac_id }} 
            @endif
            @if(request('status'))
                <b>»</b> {{ request('status') }} 
            @endif
        </h2>
		@if(Auth::user()->role != 6)
            <button class="btn btn-primary" onclick="window.location.href='{{ route('invoice', ['add']) }}'"><i class="fa fa-plus"></i> Add New</button>
		@endif
	</div>
    <div class="row mb-4">
        <div class="col-md-2">
            <select class="form-select storefilterSelect">
                <option value="" {{ !isset($_GET['store']) ? 'selected' : '' }}>Filter by Store</option>
                @foreach($all_stores as $str)
                    <option value="{{ $str->id }}" {{ isset($_GET['store']) && $_GET['store'] == $str->id ? 'selected' : '' }}>{{ $str->fac_id }} {{ $str->name ? '(' . $str->name . ')' : '' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select statusfilterSelect">
                <option value="">Filter by Payment</option>
                <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Unpaid" {{ request('status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary filterButton">Filter</button>
            <button class="btn btn-primary filter-reset">Reset</button>
        </div>

        <form class="col-md-2 ms-auto" method="get" action="{{ url()->current() }}">
            @foreach(request()->query() as $key => $value)
            @if ($key !== 'per_page')
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
            @endforeach
          <div class="form-group d-flex justify-content-end align-items-center">
            <label for="per_page" class="me-1">Items Per Page:</label>
            <select class="form-control w-44" name="per_page" id="per_page" onchange="this.form.submit()" style="max-width: 45px;">
              <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
              <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
              <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
              <option value="40" {{ request('per_page') == 40 ? 'selected' : '' }}>40</option>
              <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
              <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>All</option>
            </select>
          </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="userTable">
            <thead>
                <tr>
                    <th>Invoice No.</th> 
                    <th>Created By</th> 
                    @if(Auth::user()->role < 6)
                        <th>Updated By</th>
                    @endif
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Service Type</th>
                    <th>Payment Method</th>
                    <th>Payment Proof</th>
                    <th>Amount</th>
                    <th>Payment Status</th>   
                    <th class="text-center" style="min-width: 60px;">View/Print</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td class="align-middle">
                            @if(Auth::user()->role < 6)
                                @if($invoice->payment !== 'Paid')
                                    <a href="{{ route('invoice', ['edit' => $invoice->id]) }}">{{ $invoice->invoice_no }}</a>
                                @else
                                    {{ $invoice->invoice_no }}
                                @endif
                            @else
                                {{ $invoice->invoice_no }}
                            @endif
                        </td>
                        <td class="align-middle">{{ $invoice->createdByUser?->name }}</td>
                        @if(Auth::user()->role < 6)
                            <td class="align-middle">
                                @if($invoice->updatedBy)
                                    @if(count($invoice->updatedBy) > 1)
                                        {{ $invoice->updatedBy[0][0] }} ({{ $invoice->updatedBy[0][1] }})
                                        <span class="popovr" data-bs-content="
                                            @foreach($invoice->updatedBy as $by)
                                                {{ $by[0] }} ({{ $by[1] }}) <br>
                                            @endforeach
                                        " data-bs-toggle="popover" data-bs-placement="right" title="Updated History">
                                            <i class="fa fa-paperclip text-primary"></i>
                                        </span>
                                    @else
                                        {{ $invoice->updatedBy[0][0] }} ({{ $invoice->updatedBy[0][1] }})
                                    @endif
                                @endif
                            </td>
                        @endif
                        <td class="align-middle">{{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('m/d/Y') : '' }}</td>
                        <td class="align-middle">{{ $invoice->customer?->name }}</td>
                        <td class="align-middle">{{ $invoice->service }}</td>
                        <td class="align-middle">{{ $invoice->pay_opt == "MO" ? "Money Order" : $invoice->pay_opt }}</td>
                        <td class="align-middle">{{ $invoice->pay_opt == "Check" ? $invoice->check_no : ($invoice->pay_opt == "MO" ? $invoice->mo_no : ($invoice->pay_opt == "Cash" ? 'Cash': '')) }}</td>
                        <td class="align-middle text-end">
                            @php
                                if(count($invoice->invoice_items) > 0){
                                    $amount = 0;
                                    $insp_amount = 0;
                                    $inv_items = $invoice->invoice_items;
                                    
                                    foreach($inv_items as $item){
                                        if($item->category == 'Monthly Inspection' || $item->category == 'Calibration'){
                                            $insp_amount += $item->amount;
                                        } else {
                                            $amount += $item->amount;
                                        }
                                    }
                                    
                                    $sales_tax = $amount * config('app.sales_tax_percentage');
                                    $total = $amount + $insp_amount + $sales_tax;
                                    
                                    if($total > 0){
                                        echo '$' . number_format(round($total, 2), 2);
                                    }
                                    
                                }
                                
                                $paid_amount_arr = json_decode($invoice->paid_amount, true);
                                $paid_amount = 0;
                                if($paid_amount_arr && count($paid_amount_arr) > 0){
                                    foreach($paid_amount_arr as $paid){
                                        $paid_amount += (float)$paid[0];
                                    }
                                }
                                
                                $invoice->payment = $invoice->payment ?? 'Unpaid';
                            @endphp
                            
                        </td>
                        <td class="align-middle text-center {{ $invoice->payment == 'Unpaid' ? 'text-danger' : 'text-success' }}" style="{{ $invoice->paid_amount && $paid_amount < $invoice->invoice_items->sum('amount') ? 'color: #ec6d2b !important;' : '' }}"><b>{{ $invoice->paid_amount && $paid_amount < $invoice->invoice_items->sum('amount') ? 'Partially Paid'  : $invoice->payment }}</b></td>
                        <td class="align-middle text-center">
                            @if($invoice->file_name)
                                <button class="btn btn-primary p-0 px-1" onclick="openPdf('{{ url('/invoices') }}/{{ $invoice->file_name }}')"><i class="fa fa-eye"></i></button>
                                <a class="btn btn-primary p-0 px-1" href="{{ url('/invoices') }}/{{ $invoice->file_name }}" target="_blank" download><i class="fa fa-download"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $invoices->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>

<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">View PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <canvas id="pdfCanvas" style="width: 100%; height: auto;"></canvas>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.filterButton').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            var store = $('.storefilterSelect').val();
            var status = $('.statusfilterSelect').val();
    
            if(store){
                params.set('store', store);
            } else {
                params.delete('store');
            }
            
            if(status){
                params.set('status', status);
            } else {
                params.delete('status');
            }
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    
        $('.filter-reset').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            params.delete('store');
            params.delete('status');
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
        
        
        $('.popovr').popover({
            trigger: 'hover',
            html: true,
        });
        
        var str_filter = document.querySelector('.storefilterSelect');

        dselect(str_filter, {
            search: true
        });
    });
    
    function openPdf(url) {
        const canvas = document.getElementById('pdfCanvas');
        const context = canvas.getContext('2d');
        
        const randomString = Math.random().toString(36).substring(2, 5);
        const uniqueUrl = url + '?v=' + randomString;
        
        context.clearRect(0, 0, canvas.width, canvas.height);
        $('#pdfModal').modal('show');
    
        pdfjsLib.getDocument(uniqueUrl).promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
                const scale = 2;
                const viewport = page.getViewport({ scale: scale });
    
                canvas.height = viewport.height;
                canvas.width = viewport.width;
    
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        });
    }
</script>