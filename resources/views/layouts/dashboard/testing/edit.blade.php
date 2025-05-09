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
        <h2>Survey <b>»</b> Edit <b>»</b> {{ $testing->id }}</h2>
    </div>

    <form class="col-md-11 m-auto" id="test-form" action="{{ url('/dashboard/testing/edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name="id" value="{{ $testing->id }}">

        @for ($i = 1; $i <= 18; $i++)
            @if ($i % 2 != 0)
                <div class="row mb-3">
            @endif
                    <div class="col-md-6">
                        <label for="q_{{ $i }}" class="form-label">{{ $i }}. {{ isset($questions[$i - 1]) ? $questions[$i - 1]->question : '' }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="q_{{ $i }}" name="q_{{ $i }}" required>
                            <option value="Yes" {{ isset($meta[$i-1]) && $meta[$i-1]->answer == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ isset($meta[$i-1]) && $meta[$i-1]->answer == 'No' ? 'selected' : '' }}>No</option> 
                            <option value="N/A" {{ isset($meta[$i-1]) && $meta[$i-1]->answer == 'N/A' ? 'selected' : '' }}>N/A</option> 
                        </select>
                        <input type="text" class="form-control mt-1" id="des_{{ $i }}" name="des_{{ $i }}" placeholder="Description" value="{{ isset($meta[$i-1]) ? $meta[$i-1]->desc : '' }}">
                    </div>
            @if ($i % 2 == 0 || $i == 18)
                </div>
            @endif
        @endfor
        
        <div class="col-md-12 mb-3">
            <label for="gen-comment" class="form-label">Genaral Comment</label>
            <textarea class="form-control" id="gen-comment" name="gen_comment">{{ $testing->gen_comment }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>