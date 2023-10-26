@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <li>{{ session('success') }}</li>
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <li>{{ session('error') }}</li>
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
    </div>
@endif