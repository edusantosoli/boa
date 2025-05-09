@extends('layouts.menu')

@section('content')
<h2>Editar Conta Contábil</h2>
<br>
@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<form method="POST" action="{{ route('contas.update', $conta) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Código</label>
        <input type="text" name="codigo" class="form-control" value="{{ $conta->codigo }}" required>
    </div>
    <div class="mb-3">
        <label>Descrição</label>
        <input type="text" name="descricao" class="form-control" value="{{ $conta->descricao }}" required>
    </div>
    <button class="btn btn-primary">Atualizar</button>
    <a href="{{ route('contas.index') }}" class="btn btn-primary">Cancelar</a>
</form>
@endsection
