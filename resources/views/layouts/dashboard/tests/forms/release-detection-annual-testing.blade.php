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

    .test-item {
        box-shadow: 0 2px 5px #0000001f;
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>{{ isset($_GET['edit']) ? 'Edit' : 'Add' }} <b>»</b> Release Detection Annual Testing</h2>
    </div>

    <form class="col-md-11 m-auto" id="test-form" action="{{ isset($_GET['edit']) ? url('/dashboard/tests/add/release-detection-annual-testing?edit=') . $_GET['edit'] : url('/dashboard/tests/add/release-detection-annual-testing') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Customer <span class="text-danger">*</span></label>
                <select class="form-select" id="customer_id" name="customer_id" required>
                    <option value="">- Select Customer -</option>
                    @foreach($customers_all as $customer)
                        <option value="{{ $customer->id }}" {{ $testing?->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->fac_id }})</option>
                    @endforeach
                </select> 
            </div>
            <div class="col-md-6">
                <label class="form-label">Technician <span class="text-danger">*</span></label>
                <select class="form-select" id="tech_id" name="tech_id" required>
                    <option value="">- Select Technician -</option>
                    @foreach($technicians_all as $technician)
                        <option value="{{ $technician->id }}" {{ $testing?->tech_id == $technician->id ? 'selected' : '' }}>{{ $technician->name }}</option>
                    @endforeach
                </select> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" value="{{ $testing?->date }}">
            </div>
        </div>

        <div class="mt-4 mb-3 pt-2 pb-0 px-4 rounded" style="border: 2px solid #53884047;">
            <h4 class="text-center mb-3">Component Tested</h4>
            <div>
                @if($testing)
                    @foreach($testing->testing_meta->take(5) as $key => $meta)
                        <div class="row mb-2 p-2 rounded-1 border border-light-subtle test-item">
                            <div class="col-md-6">
                                <b>{{ $rda_comp_tests[$key]['test'] }}</b>
                                <span>{{ $rda_comp_tests[$key]['description'] }}</span>
                                <input type="hidden" class="descript">
                            </div>
                            <div class="col-md-2">
                                <label>Meets Criteria? <span class="text-danger">*</span></label>
                                <select class="form-select meets_criteria" required>
                                    <option value="Y" {{ $meta->meets_criteria == 'Y' ? 'selected' : '' }}>Yes</option>
                                    <option value="N" {{ $meta->meets_criteria == 'N' ? 'selected' : '' }}>No</option>
                                </select> 
                            </div>
                            <div class="col-md-2">
                                <label>Needs Action? <span class="text-danger">*</span></label>
                                <select class="form-select needs_action" required>
                                    <option value="Y" {{ $meta->needs_action == 'Y' ? 'selected' : '' }}>Yes</option>
                                    <option value="N" {{ $meta->needs_action == 'N' ? 'selected' : '' }}>No</option>
                                </select> 
                            </div>
                            <div class="col-md-2">
                                <label>Action Taken?</label>
                                <input type="text" class="form-control action_taken" placeholder="N/A" value="{{ $meta->action_taken }}">
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach($rda_comp_tests as $test)
                        <div class="row mb-2 p-2 rounded-1 border border-light-subtle test-item">
                            <div class="col-md-6">
                                <b>{{ $test['test'] }}</b>
                                <span>{{ $test['description'] }}</span>
                                <input type="hidden" class="descript">
                            </div>
                            <div class="col-md-2">
                                <label>Meets Criteria? <span class="text-danger">*</span></label>
                                <select class="form-select meets_criteria" required>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select> 
                            </div>
                            <div class="col-md-2">
                                <label>Needs Action? <span class="text-danger">*</span></label>
                                <select class="form-select needs_action" required>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select> 
                            </div>
                            <div class="col-md-2">
                                <label>Action Taken?</label>
                                <input type="text" class="form-control action_taken" placeholder="N/A">
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="mt-4 mb-3 pt-2 pb-0 px-4 rounded" style="border: 2px solid #53884047;">
            <h4 class="text-center mb-3">Other Component Tested</h4>
            <div id="item-cont">
                @if($testing)
                    @foreach($testing->testing_meta->skip(5) as $meta)
                        <div class="row mb-2 p-2 rounded-1 border border-light-subtle test-item">
                            <div class="col-md-5">
                                <label>Title</label>
                                <input type="text" class="form-control descript" placeholder="Decsription" value="{{ $meta->descript }}">
                            </div>
                            <div class="col-md-2">
                                <label>Meets Criteria?</label>
                                <select class="form-select meets_criteria">
                                    <option value="Y" {{ $meta->meets_criteria == 'Y' ? 'selected' : '' }}>Yes</option>
                                    <option value="N" {{ $meta->meets_criteria == 'N' ? 'selected' : '' }}>No</option>
                                </select> 
                            </div>
                            <div class="col-md-2">
                                <label>Needs Action?</label>
                                <select class="form-select needs_action">
                                    <option value="Y" {{ $meta->needs_action == 'Y' ? 'selected' : '' }}>Yes</option>
                                    <option value="N" {{ $meta->needs_action == 'N' ? 'selected' : '' }}>No</option>
                                </select> 
                            </div>
                            <div class="col-md-2">
                                <label>Action Taken?</label>
                                <input type="text" class="form-control action_taken" placeholder="N/A" value="{{ $meta->action_taken }}">
                            </div>

                            <div class="col-md-1 d-flex">
                                <button type="button" class="btn-close close-item m-auto" aria-label="Close" disabled></button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row mb-2 p-2 rounded-1 border border-light-subtle test-item">
                        <div class="col-md-5">
                            <label>Title</label>
                            <input type="text" class="form-control descript" placeholder="Decsription">
                        </div>
                        <div class="col-md-2">
                            <label>Meets Criteria?</label>
                            <select class="form-select meets_criteria">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select> 
                        </div>
                        <div class="col-md-2">
                            <label>Needs Action?</label>
                            <select class="form-select needs_action">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select> 
                        </div>
                        <div class="col-md-2">
                            <label>Action Taken?</label>
                            <input type="text" class="form-control action_taken" placeholder="N/A">
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

            <input type="hidden" id="tests" name="tests" value="">
        </div>

        <button type="button" id="frm_submit" class="btn btn-primary mt-2">{{ isset($_GET['edit']) ? 'Update' : 'Create' }}</button>
    </form>
</div>


<script>

    $('#add_item').on('click', function() {
        var new_item = $(`<div class="row mb-2 p-2 rounded-1 border border-light-subtle test-item">
                    <div class="col-md-5">
                        <label>Title</label>
                        <input type="text" class="form-control descript" placeholder="Decsription">
                    </div>
                    <div class="col-md-2">
                        <label>Meets Criteria?</label>
                        <select class="form-select meets_criteria">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select> 
                    </div>
                    <div class="col-md-2">
                        <label>Needs Action?</label>
                        <select class="form-select needs_action">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select> 
                    </div>
                    <div class="col-md-2">
                        <label>Action Taken?</label>
                        <input type="text" class="form-control action_taken" placeholder="N/A">
                    </div>

                    <div class="col-md-1 d-flex">
                        <button type="button" class="btn-close close-item m-auto" aria-label="Close" disabled></button>
                    </div>
                </div>`);

        new_item.appendTo('#item-cont');
        
        update_cus_id_options();

        $('.test-item .close-item').prop('disabled', false);
    });

    $(document).on('click', '.close-item', function() {
        if($('body .close-item').length !== 1){
            this.closest('.test-item').remove();
        }
    });

    $('#frm_submit').on('click', function(e) {

        event.preventDefault(e);

        var form = $('#test-form')[0];

        if (form.checkValidity() === false) {
            form.reportValidity();
            return;
        }
        
        var tests = [];

        $('.test-item').each(function(index) {
            var $test_item = $(this);
            var descript = $test_item.find('.descript').val();
            var meets_criteria = $test_item.find('.meets_criteria').val();
            var needs_action = $test_item.find('.needs_action').val();
            var action_taken = $test_item.find('.action_taken').val();

            var dataSet = {
                descript: descript,
                meets_criteria: meets_criteria,
                needs_action: needs_action,
                action_taken: action_taken,
            };

            tests.push(dataSet);
        });

        $('#tests').val( JSON.stringify(tests) );
        $('#test-form').submit();
    });

    var customer_id = document.querySelector('#customer_id');

    dselect(customer_id, {
        search: true
    });

    var tech_id = document.querySelector('#tech_id');

    dselect(tech_id, {
        search: true
    });

</script>