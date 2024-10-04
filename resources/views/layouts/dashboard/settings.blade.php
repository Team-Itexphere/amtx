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
        <h2>Settings</h2>
    </div>
    <div class="row mb-3 justify-content-center">
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <form id="ft-ad" action="{{ url('/dashboard/settings/edit') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="mb-4 d-flex justify-content-between">
                    <div class="crpcontainer">
						
						<label for="site_logo" class="form-label">Site Logo (512 x 512px)</label>
                        <input type="file" class="form-control" id="site_logo" name="site_logo" accept="jpeg" onchange="crprst();">						
		
					</div>                    
                    <div class="col-md-6">
                        <div class="text-center">
                            <h6>Curent Site Logo</h6>
                            <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" class="rounded shadow m-auto" style="max-width:50%; height:auto;">
                        </div>
                    </div>
                </div>
                <div class="mb-4 d-flex justify-content-between">
                    <div class="crpcontainer">
						
						<label for="favicon" class="form-label">Favicon (512 x 512px)</label>
                        <input type="file" class="form-control" id="favicon" name="favicon" accept="jpeg" onchange="crprst();">

					</div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <h6>Curent Favicon</h6>
                            <img src="{{ asset('img') }}/{{ config('app.favicon', 'Laravel') }}" class="rounded shadow m-auto" style="max-width:50%; height:auto;">
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="site_name" name="site_name" value="{{ config('app.name', 'Laravel') }}" required>
                </div>
                <div class="mb-4">
                    <label for="country_code" class="form-label">Country Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="country_code" name="country_code" value="{{ config('app.country_code', 'Laravel') }}" max="5" required>
                </div>
                <label for="new-number" class="form-label">Mandatory Numbers (Add numbers separated by comma)</label>
                <div class="input-group mb-4">                    
                    <span class="input-group-text apply-cc">{{ config('app.country_code', 'Laravel') }}</span>
                    <input type="text" id="main_numbers" name="main_numbers" class="form-control" placeholder="New mobile number" data-role="tagsinput" value="{{ implode(',', [config('app.main_numbers', 'Laravel')]) }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready( function(){

    $('#main_numbers').tagsinput();

    $('#country_code').on('input', function(){
        $('.apply-cc').text($(this).val());
    });
});
</script>