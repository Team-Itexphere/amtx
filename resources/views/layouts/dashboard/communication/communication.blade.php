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
    <div class="d-flex justify-content-center align-items-center mb-5">
        <h2>Communication</h2>
    </div>
    <div class="row mb-3 justify-content-center">
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <form class="col-md-11 m-auto" action="{{ url('/dashboard/communication/send') }}" method="POST">
            @csrf
                <div class="mb-3">
                    <label for="rec_role" class="form-label">Select User Role/s <span class="text-danger">*</span></label>
                    <select class="form-select" id="rec_role" name="rec_role[]" placeholder="Select User Role/s" multiple required>
                        <option vlue="All">ALL</option>
                        <option vlue="Supervisor">Supervisor only</option>
                        <option vlue="Managers">Managers only</option>
                        <option vlue="Employees">Employees only</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="rec_store" class="form-label">Select Store/s <span class="text-danger">*</span></label>
                    <select class="form-select" id="rec_store" name="rec_store[]" placeholder="Select Store/s" multiple required>
                        <option vlue="">ALL</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Type your message here..." maxlength="100" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
var rec_role = document.querySelector('#rec_role');
var rec_store = document.querySelector('#rec_store');

dselect(rec_role, {
    search: true
});

dselect(rec_store, {
    search: true
});
</script>

<script>
$('#rec_role, #rec_store').on('change', function(){

    if ( $(this).next().find('.dselect-tag[data-dselect-value="ALL"] .dselect-tag-remove').length > 0 ) {

        $(this).next().find('.dselect-tag:not([data-dselect-value="ALL"]) .dselect-tag-remove').click();
        $(this).next().find('button.dropdown-item').prop('disabled', true);

    }

});
</script>