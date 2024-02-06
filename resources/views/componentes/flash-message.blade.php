@if(session('success'))
    <div class="alert alert-success alert-dismissible bg-success text-white fade show p-3" role="alert">
        <span>{{ session('success') }}</span>
        {{-- <li>{{ session('success') }}</li> --}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible bg-danger text-white fade show p-3" role="alert">
        <span>{{ session('error') }}</span>
        {{-- <li>{{ session('error') }}</li> --}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif