@extends('layouts.menu')

@section('content')
<h2>Cadastro de Conta Contábil</h2>
<form method="POST" action="{{ route('contas.store') }}">
    @csrf
    <div class="mb-3">
        <label>Código</label>
        <input type="text" name="codigo" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Descrição</label>
        <input type="text" name="descricao" class="form-control" required>
    </div>
    <button class="btn btn-success">Salvar</button>
    <a href="{{ route('contas.index') }}" class="btn btn-primary">Cancelar</a>
</form>
@endsection
