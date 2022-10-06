@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Avaliações')
@section('content')
<h3>Resumo das Avaliações</h3>
<hr>
<div>
    <p>Media de Avaliações por Secretaria</p>
</div>

<div class="row">
    <div class=" col-md-6">
        <p>Unidades com maior quantidade de 5<i class="text-warning fa-solid fa-star"></i> </p>
    </div>
    <div class=" col-md-6">
        <p>Piores Avaliações</p>
    </div>
</div>
@endsection