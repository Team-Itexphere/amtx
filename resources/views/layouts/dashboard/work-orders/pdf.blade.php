<!DOCTYPE html>
<html>
<head>
    <title>Work Order Sheet - {{ $today }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        th, td {
            border: 1px solid #dee2e6;
            padding: 3px;
        }
    </style>
</head>
<body>
    <h2 class="text-center">Work Orders</h2>
    <p class="text-center mb-4">Result for <b>»</b> {{ $filters }}</p>
  
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Item Type</th>
                <th class="text-center">Item</th>
                <th class="text-center">Location</th>
                <th class="text-center">Technician</th>
                <th class="text-center">Status</th>
                <th class="text-center">Cost</th>
                <th class="text-center">Parts Replacements</th>
                <th class="text-center">Created Date</th>
                <th class="text-center">Closed Date</th>
            </tr>
        </thead>
        <tbody>
            @if($work_orders)
                @foreach($work_orders as $work_order)
                    <tr>
                        <td class="align-middle">{{ $work_order->type }}</td>
                        <?php
                            if($work_order->type == 'Equipment') {
                              $item_name = $work_order->equipment->name;
                            } elseif($work_order->type == 'Fuel Tank') {
                              $item_name = $work_order->fuel_tank->name;
                            } elseif($work_order->type == 'Pump') {
                              $item_name = $work_order->pump->pump_number;
                            } elseif($work_order->type == 'General') {
                              $item_name = $work_order->general_item;
                            }
                        ?>
                        <td class="align-middle">{{ $item_name }}</td>
                        <td class="align-middle">{{ $work_order->store->name }}</td>
                        <td class="align-middle">{{ $work_order->assign_tech }}</td>
                        <td class="align-middle text-center">
                          <span class="d-inline-block {{ ($work_order->status == 'Closed' ? 'bg-success text-white' : ($work_order->status == 'RTR' ? 'bg-primary text-white' : 'bg-danger text-white')) }} rounded" style="padding: 5px; min-width: 50px;">{{ ($work_order->status == 'Open' ? 'Open' : ($work_order->status == 'RTR' ? 'RTR' : 'Closed')) }}</span>
                        </td>
                        <td class="align-middle text-end">
                        @if($work_order->cost != '')
                          $<span>{{ $work_order->cost }}</span>
                        @endif
                        </td>
                        <td class="align-middle">{{ $work_order->parts_repl }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($work_order->create_date)->format('m/d/Y') }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($work_order->closed_date)->format('m/d/Y') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
  
</body>
</html>