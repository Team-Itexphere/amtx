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

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Pictures</h2>
        <!--button class="btn btn-primary" onclick="window.location.href='{{ route('employees', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button-->
    </div>
    
    @php
        $pictures = $user->pictures()->get()->groupBy('type');
    @endphp
    <div class="container mt-5 col-md-11 mx-auto">
            <div class="mt-3 d-flex flex-wrap gap-4 justify-content-center">
                {{-- Loop through each type and create a card if the type exists --}}
                @foreach (array_filter(['picture', 'atgs', 'atgi', $user->rec_logs ? 'rec_log' : null]) as $type)
                        <div class="card mb-4" style="width: 13rem; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modal-{{ $type }}">
                            @if($type == 'picture')
                                <span class="text-center" style="font-size: 114px;">📷</span>
                            @elseif($type == 'atgs')
                                <span class="text-center" style="font-size: 114px;">📊</span>
                            @elseif($type == 'atgi')
                                <span class="text-center" style="font-size: 114px;">📦</span>
                            @elseif($type == 'rec_log')
                                <span class="text-center" style="font-size: 114px;">📝</span>
                            @endif
                            <div class="card-body text-center">
                                <a href="#" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#modal-{{ $type }}">
                                    {{ $type == 'picture' ? 'Pictures' : ($type == 'atgs' ? 'Sensor/CSLD Tickets' : ($type == 'atgi' ? 'Inventory Tickets' : 'Rectifier Log')) }}
                                </a>
                            </div>
                        </div>
    
                        {{-- Modal for the type --}}
                        <div class="modal fade" id="modal-{{ $type }}" tabindex="-1" aria-labelledby="modalLabel-{{ $type }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel-{{ $type }}">{{ $type == 'picture' ? 'Pictures' : ($type == 'atgs' ? 'Sensor/CSLD Tickets' : ($type == 'atgi' ? 'Inventory Tickets' : 'Rectifier Log')) }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            @if($pictures->has($type))
                                                @foreach ($pictures[$type] as $picture)
                                                    <div class="col-4 mb-3">
                                                        <img src="{{ url('') . $picture->image }}" alt="{{ $type }}" class="img-fluid rounded">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endforeach
            </div>
    </div>
</div>