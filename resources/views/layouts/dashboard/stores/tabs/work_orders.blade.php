    <div class="row mb-4">
        <div class="col-md-8 d-flex"> 
            <div class="col-md-4 me-2">
                <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) && request()->has('wo') ? $_GET['s'] : '' }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary filterButton">Filter</button>
                <button class="btn btn-primary filter-reset">Reset</button>
            </div>
        </div>
        
        <div class="col-md-4 d-flex">            
            <form class="col-md-6 ms-auto me-2" method="get" action="{{ url()->current() }}">
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
            <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('work-orders', ['list']) }}'"><i class="fa fa-external-link"></i> Manage Orders</button>
        </div>
    </div> 
    <div class="table-responsive"> 
        <table class="table table-bordered" id="orderTable">
            <thead>
                <tr>
                    <th>Item Type</th>
                    <th>Item</th>
                    <th>Location</th>
                    <th>Technician</th>
                    <th>Status</th>
                    <th>Cost</th>
                    <th>Parts Replacements</th>
                    <th>Created Date</th>
                    <th>Closed Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                $s = isset($_GET['s']) && request()->has('wo') ? $_GET['s'] : null;
                $work_orders = paginateCollection( filterRecords(\App\Models\Work_orders::class, null, null, null, $s) );
                @endphp
                @if($work_orders)
                    @foreach($work_orders as $work_order)
                        <tr>
                            <td class="align-middle">{{ $work_order->type }}</td>
                            <td class="align-middle">
                                @if($work_order->type == 'Equipment')
                                    {{ $work_order->equipment->name }}
                                @elseif($work_order->type == 'Fuel Tank')
                                    {{ $work_order->fuel_tank->name }}
                                @elseif($work_order->type == 'Pump')
                                    {{ $work_order->pump->pump_number }}
                                @elseif($work_order->type == 'General')
                                    {{ $work_order->general_item }}
                                @endif
                            </td>
                            <td class="align-middle">{{ $work_order->store->name }}</td>
                            <td class="align-middle">{{ $work_order->assign_tech }}</td>
                            <td class="align-middle text-center">
                          <span class="d-inline-block {{ ($work_order->status == 'Closed' ? 'bg-success text-white' : ($work_order->status == 'RTR' ? 'bg-primary text-white' : 'bg-danger text-white')) }} rounded" style="padding: 5px; min-width: 50px;">
                            {{ ($work_order->status == 'Open' ? 'Open' : ($work_order->status == 'RTR' ? 'RTR' : 'Closed')) }}
                          </span>
                        </td>
                            <td class="align-middle text-end">
                            @if($work_order->cost != '')
                            $<span>{{ $work_order->cost }}</span>
                            @endif
                            </td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($work_order->create_date)->format('m/d/Y') }}</td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($work_order->closed_date)->format('m/d/Y') }}</td>
                            <td class="align-middle">{{ $work_order->parts_repl }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <br>
        {{ $work_orders->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>