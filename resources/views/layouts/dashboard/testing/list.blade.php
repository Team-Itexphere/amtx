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

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<div class="container pt-2 bg-white rounded">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Monthly Inspections Report
            @if(isset($_GET['line']))
                <b>»</b> Under Annual Line & Leak
            @elseif(isset($_GET['stage']))
                <b>»</b> Under Stage 1
            @elseif(isset($_GET['cal']))
                <b>»</b> Under Calibration
            @elseif(isset($_GET['company']))
                <b>»</b> {{ $_GET['company'] }}
            @endif
        </h2>
    </div>
    <div class="row mb-4">
        <div class="col-md-2">
            <input type="text" class="form-control searchInput" placeholder="Search..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
        </div>
        @if(!Auth::user()->role < 4)
        <div class="col-md-2">
            @if(Auth::user()->role == 6)
                <select class="form-select" style="background: transparent; margin-bottom: -38px; position: relative;" disabled></select>
            @endif
            <select class="form-select companyfilterSelect">
                <option value="" {{ !isset($_GET['company']) ? 'selected' : '' }}>Filter by Company</option>
                <option value="AMTS" {{ isset($_GET['company']) && $_GET['company'] == 'AMTS' ? 'selected' : '' }}>AMTS</option>
                <option value="Petro-Tank Solutions" {{ isset($_GET['company']) && $_GET['company'] == 'Petro-Tank Solutions' ? 'selected' : '' }}>PTS</option>
            </select>
        </div>
        @endif
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
                    <th>Date</th>
                    <th>Inspection Type</th>
                    <th>Customer</th>
                    <th>Technician</th>
                    <th class="text-center">Inspection Doc.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testings as $testing)
                    <tr>
                        <td class="align-middle">{{  $testing->updated_at->format('m/d/Y h:i:s A') }}</td>
                        <td class="align-middle">{{ $testing->ro_location->route->insp_type }} Report</td>
                        <td class="align-middle">{{ $testing->customer ? $testing->customer->name : '' }} ({{ $testing->customer->fac_id }})</td>
                        <td class="align-middle">{{ $testing->technician ? $testing->technician->name : '' }}</td>
                        <td class="align-middle text-center">
                            <button class="btn btn-primary p-0 px-1" onclick="openPdf('{{ route('testing', ['pdf' => $testing->id]) }}')"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('testing', ['pdf' => $testing->id, 'download']) }}'"><i class="fa fa-download"></i></button>
                            @if(Auth::user()->role < 4)
                                <button class="btn btn-primary p-0 px-1" onclick="window.location.href='{{ route('testing', ['edit' => $testing->id]) }}'"><i class="fa fa-edit"></i></button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $testings->appends($_GET)->links('pagination::bootstrap-5') }}
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
    
            var s = $('.searchInput').val();
            var company = $('.companyfilterSelect').length > 0 ? $('.companyfilterSelect').val() : '';
    
            if(s != ''){
                params.set('s', s);
            } else {
                params.delete('s');
            }
            
            if(company){
                params.set('company', company);
            } else {
                params.delete('company');
            }
            
            params.delete('page');
    
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    
        $('.filter-reset').click(function() {
            let currentUrl = new URL(window.location.href);
            let params = new URLSearchParams(currentUrl.search);
    
            params.delete('s');
            params.delete('company');
            params.delete('page');
            
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
        });
    });
    
    function openPdf(url) {
        const canvas = document.getElementById('pdfCanvas');
        const context = canvas.getContext('2d');

        context.clearRect(0, 0, canvas.width, canvas.height);
        $('#pdfModal').modal('show');

        pdfjsLib.getDocument(url).promise.then(function(pdf) {
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
