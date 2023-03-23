<a class="btnDelete" data-id="{{ $item->id }}">
    <i class="fa-xl text-danger fa-solid fa-trash"></i>
</a>
{{-- <button class="btnDelete btn" data-id="{{ $item->id }}">
    <i class="fa-xl text-danger fa-solid fa-trash"></i>
</button> --}}
<form class="d-none" id="deleteitem{{ $item->id }}"
    action="{{ route($route, $item) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
</form>