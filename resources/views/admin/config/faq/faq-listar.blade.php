@extends('template.base')

@section('titulo', 'EscutaSol - FAQs')
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
    
    @component('admin.config.components_crud.filtrar-pesquisa', ['route' => 'get-faq-list'])
    @endcomponent
    
    <table class="table table-striped">
        <thead>
            <th></th>
            <th>Id</th>
            <th>Ativo</th>
            <th>Pergunta</th>
            <th>Última alteração</th>
            <th class="text-center">Ações</th>
        </thead>
        <tbody id="tabela" class="table-group-divider">
            @forelse ($faqs as $faq)
            <tr id="{{ $faq->id }}">
                <td>
                    <i class="fa-solid fa-sort"></i>
                </td>
                <td>
                    {{ $faq->id }}
                </td>
                <td>
                        @if ($faq->ativo)
                        <a class="btn" href="{{ route('get-toggle-faq-status', ['id' => $faq->id]) }}">
                                <i class="text-success fa-solid fa-circle-check"></i>
                            </a>
                            @else
                            <a class="btn" href="{{ route('get-toggle-faq-status', ['id' => $faq->id]) }}">
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
                            @component('admin.config.components_crud.view', ['item' => $faq], ['route' => 'get-faq-view'])
                            @endcomponent
                            @component('admin.config.components_crud.edit', ['item' => $faq], ['route' => 'get-edit-faq-view'])
                            @endcomponent
                            @component('admin.config.components_crud.delete', ['item' => $faq], ['route' => 'delete-delete-faq'])
                            @endcomponent
                        </div>
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center table-warning">
                    Nenhum resultado encontrado!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
        
    <div class='mx-auto'>
        {{ $faqs->links('pagination::bootstrap-4') }}
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
                        <button id="btnDeleteConfirm" type="button" class="btn btn-danger">Deletar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script nonce="{{ app('csp-nonce') }}">
        var FaqID = 0;

        $('#btnLimpaForm').click(function() {
            $('#pesquisa').val('');
        });

        $('.btnDelete').click(function() {
            deleteFaq($(this).data('id'));
        });

        function deleteFaq(id) {
            $("#deleteName").text($("#" + id + " .name").text());
            $('#deleteModal_3').modal('show');
            FaqID = id;
        }

        $("#btnDeleteConfirm").click(function() {
            $('#deleteModal_3').modal('hide');
            $('#deleteFaq' + FaqID).submit();
            FaqID = 0;
        });

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
