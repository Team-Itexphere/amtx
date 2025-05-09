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

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
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

    .loc-item {
        box-shadow: 0 2px 5px #0000001f;
        cursor: move;
    }
    
    .select2-container--default .select2-selection--single {
        border: 1px solid #ced4da !important;
        height: 38px;
    }
    .select2-selection__rendered {
        padding-top: 3px;
        height: 38px;
    }
    .select2-selection__arrow {
        height: 36px !important;
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Edit Route</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('routes', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <form class="col-md-11 m-auto" id="route-form" action="{{ url('/dashboard/route/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name="ro_id" value="{{ $route->id }}">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="num" class="form-label">Route # <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="num" name="num" value="{{ $route->num }}">
            </div>
            <div class="col-md-4">
                <label for="name" class="form-label">Route Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $route->name }}">
            </div>
            <div class="col-4">
                <label for="insp_type" class="form-label">Inspection Type <span class="text-danger">*</span></label>
                <select class="form-select" id="insp_type" name="insp_type" required>
                    <option value="Monthly Inspection" {{ $route->insp_type == 'Monthly Inspection' ? 'selected' : '' }}>Monthly Inspection</option>
                    <option value="Yearly Inspection" {{ $route->insp_type == 'Yearly Inspection' ? 'selected' : '' }}>Yearly Inspection</option>                  
                </select>
            </div>
        </div>

        
        <div class="mt-4 mb-3 pt-2 pb-0 px-4 rounded" style="border: 2px solid #53884047;">
            <h4 class="text-center mb-3">Locations</h4>
            <div id="item-cont">
                @foreach($route->ro_locations as $location)
                    <div class="row mb-2 p-2 rounded-1 border border-light-subtle loc-item" data-id="{{ $location->id }}">
                        <div class="col-md-6">
                            <select class="form-select cus_id select2" required>
                                @foreach($customers_all as $customer)
                                    <option value="{{ $customer->id }}" {{ $customer->id == $location->cus_id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->fac_id }})</option>
                                @endforeach
                            </select> 
                        </div>
                        <div class="col-md-5">
                            <input type="number" class="form-control amount" placeholder="Invoice Amount"value="{{ $location->amount }}" required>
                        </div>
                        <div class="col-md-1 d-flex">
                            <button type="button" class="btn-close close-item m-auto" aria-label="Close"></button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center" id="add-btn-cont">
                <div class="border border-2 border-success rounded-pill btn-divider">
                    <button type="button" id="add_item" class="btn btn-primary py-1 px-2"  aria-label="Add"><i class="fa fa-plus"></i></button>
                </div>
            </div>

            <input type="hidden" id="locations" name="locations" value="{{ $route->locations }}">
        </div>

        <button type="button" id="frm_submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>


<script>
    $('.select2').select2();

    const sortable = new Sortable(document.getElementById('item-cont'), {
        animation: 150,
        handle: ".loc-item",
        onEnd: function (evt) {
            // Get the new order of items
            const itemOrder = $("#item-cont .loc-item").map(function () {
                return $(this).data("id");
            }).get();

            console.log("New order:", itemOrder);
        }
    });

    function update_cus_id_options() {
        var selectedValues = [];
        $('.cus_id').each(function() {
            selectedValues.push($(this).val());
        });

        $('.cus_id').each(function() {
            var $field = $(this);
            var currentVal = $field.val();

            $field.find('option').each(function() {
                var $option = $(this);
                if (selectedValues.includes($option.val()) && $option.val() !== currentVal) {
                    $option.hide();
                } else {
                    $option.show();
                }
            });
        });
    }

    update_cus_id_options();

    $(document).on('change', '.cus_id', function() {
        update_cus_id_options();
    });
    
    $('#add_item').on('click', function() {
        var new_item = $(`<div class="row mb-2 p-2 rounded-1 border border-light-subtle loc-item">
                    <div class="col-md-6">
                        <select class="form-select cus_id select2" required>
                            <option value="">- Select Customer -</option>
                            @foreach($customers_all as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->fac_id }})</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control amount" placeholder="Invoice Amount" required>
                    </div>
                    <div class="col-md-1 d-flex">
                        <button type="button" class="btn-close close-item m-auto" aria-label="Close"></button>
                    </div>
                </div>`);

        new_item.appendTo('#item-cont');
        
        update_cus_id_options();

        $('.loc-item .close-item').prop('disabled', false);
        $('.select2').select2();
    });

    $(document).on('click', '.close-item', function() {
        if($('body .close-item').length !== 1){
            this.closest('.loc-item').remove();
        }
    });

    $('#frm_submit').on('click', function(e) {

        event.preventDefault(e);

        var form = $('#route-form')[0];

        if (form.checkValidity() === false) {
            form.reportValidity();
            return;
        }
        
        var locations = [];

        $('.loc-item').each(function(index) {
            var $route_item = $(this);
            var cus_id = $route_item.find('.cus_id').val();
            var amount = $route_item.find('.amount').val();

            var dataSet = {
                cus_id: cus_id,
                amount: amount,
            };

            locations.push(dataSet);
        });

        $('#locations').val( JSON.stringify(locations) );
        $('#route-form').submit();
    });

</script>