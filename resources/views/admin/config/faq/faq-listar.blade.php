@extends('template.base')

@section('content')
    <div id="mensagem">
    </div>
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">FAQs</h1>
        <a class="btn btn-primary" href="{{ route('get-create-faq') }}">
            <i class="fa-solid fa-plus me-1"></i>
            Novo FAQ
        </a>
    </div>
    <hr>

    <form class="" action="{{ route('get-faq-list') }}" method="GET">
        <div class="m-0 p-0 row">
            <div class="col-md-5">
                <label for="pesquisa">Nome:</label>
                <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                    value="{{ request()->pesquisa }}">
            </div>
    
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary form-control mt-3" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a class="btn btn-warning form-control mt-3" onclick="$('#pesquisa').val('')">
                    Limpar
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </div>
    
    </form>

    <table class="table table-striped">
        <thead>
            <th></th>
            <th>Id</th>
            <th>Ativo</th>
            <th>Pergunta</th>
            <th>Última alteração</th>
            <th class="text-center">Ações</th>
        </thead>
        <tbody id="tabela">
            @if (isset($faqs) && count($faqs) > 0)
                @foreach ($faqs as $faq)
                    <tr id="{{ $faq->id }}">
                        <td>
                            <i class="fa-solid fa-sort"></i>
                        </td>
                        <td>
                            {{ $faq->id }}
                        </td>
                        <td>
                            @if ($faq->ativo)
                                <a class="btn"
                                    href="{{ route('get-toggle-faq-status', ['id' => $faq->id]) }}">
                                    <i class="text-success fa-solid fa-circle-check"></i>
                                </a>
                            @else
                                <a class="btn"
                                    href="{{ route('get-toggle-faq-status', ['id' => $faq->id]) }}">
                                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                                </a>
                            @endif
                        </td>
                        <td class="name">
                            {{ substr($faq->pergunta, 0, 100) . '...' }}
                        </td>
                        <td>
                            {{ Carbon\Carbon::parse($faq->updated_at)->format('d/m/Y \à\s H:i\h') }}
                        </td>
                        <td class="col-md-1">
                            <div class="d-flex justify-content-evenly">
                                <a href="{{ route('get-faq-view', ['id' => $faq->id]) }}">
                                    <i class="fa-xl fa-solid fa-magnifying-glass text-primary"></i>
                                </a>
                                <a href="{{ route('get-edit-faq-view', ['id' => $faq->id]) }}">
                                    <i class="fa-xl fa-solid fa-pen-to-square text-warning"></i>
                                </a>
                                <a href="javascript:deletar({{ $faq->id }})">
                                    <i class="fa-xl fa-solid fa-trash text-danger"></i>
                                </a>
                                <form class="d-none" id="deleteFaq{{ $faq->id }}"
                                    action="{{ route('delete-delete-faq', $faq) }}"
                                    method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <p>não existem registros</p>
            @endif
        </tbody>
    </table>
    <div class='mx-auto'>
        {{ $faqs->links('pagination::bootstrap-4') }}
    </div>
    </div>
    <div id="deleteModal_3" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-light">Deletar FAQ!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente deletar a pergunta: <span id="deleteName" class="fw-bold"></span>
                    </p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" onclick="close_modal()">Deletar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    var FaqID = 0;

        function deletar(id) {
            $("#deleteName").text($("#" + id + " .name").text());
            $('#deleteModal_3').modal('show');
            FaqID = id;
        }

        function close_modal() {
            $('#deleteModal_3').modal('hide');
            $('#deleteFaq' + FaqID).submit();
        }

        $(document).ready(function() {
            $(function() {
                $("#tabela").sortable({
                    update: function() {
                        var ordem_atual = $(this).sortable("toArray");
                        $.ajax({
                            url: "{{ route('order-faq') }}",
                            type: "post",
                            dataType: 'json',
                            data: {
                                'ordem': ordem_atual,
                                "_token": "{{ csrf_token() }}",
                            }
                        }).done(function(reponse) {
                            if (true) {
                                $('#mensagem').html(`
                                    <div class="alert alert-success" role="alert">
                                        FAQ ordenado com sucesso!
                                    </div>
                                    `);
                                $("#mensagem").slideDown('slow');
                                retirarMsg();
                            }
                        });
                    }
                });
            });


            //Retirar a mensagem após 1700 milissegundos
            function retirarMsg() {
                setTimeout(function() {
                    $("#mensagem").slideUp('slow', function() {});
                }, 1700);
            }
        });
</script>
@endpush