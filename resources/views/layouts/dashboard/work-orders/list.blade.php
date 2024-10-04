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
<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Work Orders <b>»</b> {{ isset($_GET['comp']) ? 'Completed' : 'Active' }}</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('work-orders', ['add']) }}'"><i class="fa fa-plus"></i> Add New</button>
    </div>
    <div class="row mb-4">
        <div class="col-md-2">
            <select class="form-select priofilterSelect">
                <option value="" {{ !isset($_GET['prio']) ? 'selected' : '' }}>Filter by Priority</option>
                <option value="High" {{ isset($_GET['prio']) && $_GET['prio'] == 'High' ? 'selected' : '' }}>High</option>
                <option value="Medium" {{ isset($_GET['prio']) && $_GET['prio'] == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="Low" {{ isset($_GET['prio']) && $_GET['prio'] == 'Low' ? 'selected' : '' }}>Low</option>
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
                    <th>Work Order No</th> 
                    @if(Auth::user()->role < 6)
                        <th>Created By</th>
                    @endif
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Technician</th>
                    <th>Date</th>
                    <th>Time</th>
                    @if(isset($_GET['comp']))
                        <th>Completed Date</th>
                        <th>Completed Time</th>
                    @endif
                    <th>{{ Auth::user()->role < 6 ? 'Customer\'s comment' : 'Comment'}}</th>
                    @if(Auth::user()->role < 6)
                        <th>Priority</th>
                    @endif
                    <th>Office Comment</th>
                    <th class="text-center">Images</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $has_calls = false;
                @endphp
                @foreach($work_orders as $work_order)
                    @php
                        if( count($work_order->service_calls) > 0 ){
                            $has_calls = true;                        
                        }
                    @endphp
                    <tr>
                        <td class="align-middle">
                            @if(Auth::user()->role < 5 || !isset($_GET['comp']))
                                <a href="{{ route('work-orders', ['edit' => $work_order->id]) }}">{{ $work_order->wo_number }}</a>
                            @else
                                {{ $work_order->wo_number }}
                            @endif
                        </td>
                        @if(Auth::user()->role < 6)
                            <td class="align-middle">{{ $work_order->createdByUser?->role == 6 ? 'Customer' : 'Office' }}</td>
                        @endif
                        <td class="align-middle">{{ $work_order->customer->name }}</td>
                        <td class="align-middle">{{ $work_order->status }}</td>
                        <td class="align-middle">{{ $work_order->technician ? $work_order->technician->name : '' }}</td>
                        <td class="align-middle">{{ $work_order->date ? \Carbon\Carbon::parse($work_order->date)->format('m/d/Y') : '' }}</td>
                        <td class="align-middle">{{ $work_order->time ? \Carbon\Carbon::parse($work_order->time)->format('h:i A') : '' }}</td>
                        @if(isset($_GET['comp']))
                            <td class="align-middle">{{ $work_order->comp_date ? \Carbon\Carbon::parse($work_order->comp_date)->format('m/d/Y') : '' }}</td>
                            <td class="align-middle">{{ $work_order->comp_time ? \Carbon\Carbon::parse($work_order->comp_time)->format('h:i A') : '' }}</td>
                        @endif
                        <td class="align-middle">
                            @if($work_order->description)
                                @if(count($work_order->description) > 1)
                                    {{ $work_order->description[0][0] }} ({{ $work_order->description[0][1] }})
                                    <span class="cus-cmnts" data-bs-content="
                                        @foreach($work_order->description as $comnt)
                                            {{ $comnt[0] }} ({{ $comnt[1] }}) <br>
                                        @endforeach
                                    " data-bs-toggle="popover" data-bs-placement="right" title="Customer's Comments">
                                        <i class="fa fa-paperclip text-primary"></i>
                                    </span>
                                @else
                                    {{ $work_order->description[0][0] }} ({{ $work_order->description[0][1] }})
                                @endif
                            @endif
                        </td>
                        @if(Auth::user()->role < 6)    
                            <td class="align-middle">{{ $work_order->priority }}</td>
                        @endif
                        <td class="align-middle">
                            @if($work_order->comment)
                                @if(count($work_order->comment) > 1)
                                    {{ $work_order->comment[0][0] }} ({{ $work_order->comment[0][1] }})
                                    <span class="office-cmnts" data-bs-content="
                                        @foreach($work_order->comment as $comnt)
                                            {{ $comnt[0] }} ({{ $comnt[1] }}) <br>
                                        @endforeach
                                    " data-bs-toggle="popover" data-bs-placement="right" title="Office Comments">
                                        <i class="fa fa-paperclip text-primary"></i>
                                    </span>
                                @else
                                    {{ $work_order->comment[0][0] }} ({{ $work_order->comment[0][1] }})
                                @endif
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            @if( count($work_order->service_calls) > 0 )
                                <a href="#" class="service-call-btn" data-bs-toggle="modal" data-bs-target="#service_callModal" data-id="{{ $work_order->id }}">View</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $work_orders->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</div>


@if($has_calls)

<!--popup-->
<div class="modal fade" id="service_callModal" tabindex="-1" aria-labelledby="service_callModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="service_callModalLabel">Images <b>»</b> <img src="/img/loader.gif" width="20" class="info-loader" style="display: none;"><span id="order-num"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <div id="sc-img-cont" class="d-flex flex-wrap justify-content-center"></div>
        <div id="error-message" class="text-danger"></div>
        
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $('.cus-cmnts, .office-cmnts').popover({
            trigger: 'hover',
            html: true,
        });
        
        $('.service-call-btn').click(function() {
            var woId = $(this).data('id');
            
            $('#order-num').text('');
            $('#sc-img-cont img').remove();
            
            $('.info-loader').show();
            
            $.ajax({
                url: '/dashboard/work-order/service-call/images',
                type: 'GET',
                data: { id: woId },
                success: function(response) {
                    $('#order-num').text(response.wo_number);
                    
                    var imgs = response.images;
                    imgs.forEach(function(img) {
                        $('#sc-img-cont').append('<img class="col-4 p-2" src="' + img + '">');
                    });
                    
                    $('.info-loader').hide();
                },
                error: function(xhr, status, error) {
                    alert("An error occurred when getting images: " + error);
                    $('.info-loader').hide();
                }
            });
        });
    });
</script>

@endif


<script>
    $(document).ready(function() {
        $('.filterButton').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            var prio = $('.priofilterSelect').val();
    
            if(prio){
                params.set('prio', prio);
            } else {
                params.delete('prio');
            }
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    
        $('.filter-reset').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            params.delete('prio');
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    });
</script>
