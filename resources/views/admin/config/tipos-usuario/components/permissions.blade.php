<h4 class="text-primary">Permissões</h4>
<div class="row mx-0">
    @foreach ($permissionGroups as $key => $permissionGroup )
    <ul id="{{$loop->iteration}}" class="col-md-4 list-group mb-2">
        <li onclick="javascript:markAll('{{$loop->iteration}}')" class="list-group-item active">
            {{$key}}
        </li>
        @foreach ($permissionGroup as $permission)
        <li class="list-group-item">
            <input id="{{$permission}}" class="form-check-input me-1" type="checkbox" name="permissions[]"
                value="{{permission()::getPermission($permission)->id}}" @isset($role)
                @if($role->hasPermission($permission)) checked @endif
            @endisset>
            <label for="{{$permission}}" class=" form-check-label">{{ $permission }}</label>
        </li>
        @endforeach
    </ul>
    @endforeach
</div>

@push('scripts')
<script>
    function markAll(id){
        $("#"+id).children().each(function (){
            $(this).children('input').prop('checked', true);
        });
    }
</script>
@endpush