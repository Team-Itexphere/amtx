@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show z-0" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Add New Store</h2>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('stores', ['list']) }}'"><i class="fa fa-arrow-left"></i> Back To List</button>
    </div>
    <form class="col-md-11 m-auto" action="{{ url('/dashboard/stores/add') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Store Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">Status</label>
                <select class="form-select text-center" id="status" name="status">
                    <option value="Idle">Idle</option>
                    <option value="Under Construction">Under Construction</option>
                    <option value="Complete">Complete</option>
                </select>
            </div>
        </div>

        <h6 class="const-comp" style="display: none;">Mid Construction Expenses</h6>
        <div class="my-3 p-3 const-comp  expenses-cont" style="border-radius: 10px; border: 1px solid #ced4da;" style="display: none;">
            <div class="row mb-2 cost-wrap">            
                <div class="col-md-6">
                    <input type="text" class="form-control cost_name" placeholder="Name">
                </div>
                <div class="col-md-5">
                    <input type="number" class="form-control cost" placeholder="Cost ($)">
                </div> 
                <div class="col-md-1 d-flex justify-content-center">
                    <button type="button" class="btn btn-danger rem-cost"><i class="fa fa-trash-alt"></i></button>
                </div>        
            </div>
            <div class="row mt-3">            
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary add-cost"><i class="fa fa-plus"></i></button>
                </div>       
            </div>
        </div>
        <div class="row mb-3 const-comp" style="display: none;">
            <div class="col-md-4">
                <label for="blue_print" class="form-label">Upload Blue Print</label>
                <input type="file" class="form-control" id="blue_print" name="blue_print" accept=".pdf">
            </div>
            <div class="col-md-4">
                <label for="doc_1" class="form-label">Upload Doc 1</label>
                <input type="file" class="form-control" id="doc_1" name="doc_1" accept=".pdf">
            </div>
            <div class="col-md-4">
                <label for="doc_2" class="form-label">Upload Doc 2</label>
                <input type="file" class="form-control" id="doc_2" name="doc_2" accept=".pdf">
            </div>      
        </div>

        <div class="row mb-3 comple-comp" style="display: none;">
            <div class="col-md-6">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>            
        </div>
        <div class="row mb-3 compl-comp" style="display: none;">
            <div class="col-md-6">
                <label for="fax" class="form-label">Fax</label>
                <input type="text" class="form-control" id="fax" name="fax">
            </div>
            <div class="col-md-6">
                <label for="size" class="form-label">Size</label>
                <input type="text" class="form-control" id="size" name="size">
            </div>        
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="llc" class="form-label">Company Entity <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="llc" name="llc" required>
            </div>
            <div class="col-md-6">
                <label for="ein" class="form-label">EIN <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="ein" name="ein" required>
            </div>   
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>

<script>

$(document).ready(function() {

function updateExpenses() {
    var expensesArray = [];
    
    $('.cost-wrap').each(function() {
        var costName = $(this).find('.cost_name').val();
        var costValue = $(this).find('.cost').val();

        if (costName !== '' && costValue !== '') {
            expensesArray.push({ 'cost_name': costName, 'cost': costValue });
        }
    });

    $('#expenses').val(JSON.stringify(expensesArray));
}

$(document).on('input', '.cost-wrap input', function() {
    updateExpenses();
});

function checkDelete() {
    var $remCostButtons = $('.expenses-cont').find('.rem-cost');

    if ($remCostButtons.length === 1) {
        $remCostButtons.prop('disabled', true);
    } else {
        $remCostButtons.prop('disabled', false);
    }
}

checkDelete();


$('.add-cost').click(function() {
    var expenseRow = $('.cost-wrap:last');

    var newExpense = 
        '<div class="col-md-6">' +
            '<input type="text" class="form-control cost_name" placeholder="Name">' +
        '</div>' +
        '<div class="col-md-5">' +
            '<input type="number" class="form-control cost" placeholder="Cost ($)">' +
        '</div>' +
        '<div class="col-md-1 d-flex justify-content-center">' +
            '<button type="button" class="btn btn-danger rem-cost"><i class="fa fa-trash-alt"></i></button>' +
        '</div>';

    expenseRow.after('<div class="row mb-2 cost-wrap">' + newExpense + '</div>');

    checkDelete();
});

$(document).on('click', '.rem-cost', function() {
    var expenseRow = $(this).closest('.row');
    expenseRow.remove();
    updateExpenses();
    checkDelete();
});

function toggleComp(){
    var status = $('#status').val();

    if( status == 'Idle' ) {

        $('.idle-comp').show();
        $('.const-comp, .compl-comp').hide();

    } else if ( status == 'Under Construction' ) {

        $('.const-comp').show();
        $('.idle-comp, .compl-comp').hide();

    } else if ( status == 'Complete' ) {
        
        $('.compl-comp').show();
        $('.idle-comp, .const-comp').hide();

    }
}

toggleComp();

$('#status').change(toggleComp);

});

</script>