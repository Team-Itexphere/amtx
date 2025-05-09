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
        <h2>Questions <b>»</b> Edit</h2>
    </div>

    <form class="col-md-11 m-auto" id="test-form" action="{{ url('/dashboard/testing/edit-questions') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @for ($i = 1; $i <= 18; $i++)
            @if ($i % 2 != 0)
                <div class="row mb-3">
            @endif
                    <div class="col-md-6">
                        <label for="q_{{ $i }}" class="form-label">Question {{ $i }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="q_{{ $i }}" name="q_{{ $i }}" value="{{ isset($questions[$i - 1]) ? $questions[$i - 1]->question : '' }}" required>
                    </div>
            @if ($i % 2 == 0 || $i == 18)
                </div>
            @endif
        @endfor

        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>