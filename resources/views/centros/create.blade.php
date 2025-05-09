@extends('layouts.menu')

@section('content')
<h1>Criar Centro de Custo</h1>

@if ($errors->any())
    <div>
        <strong>Erros encontrados:</strong>
        <ul>
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('centros.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="codigo">Codigo:</label>
        <input type="text" name="codigo" class="form-control" id="codigo" value="{{ old('codigo') }}" required>
    </div>

    <div class="mb-3">
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" class="form-control" id="descricao">{{ old('descricao') }}</input>
    </div>

    <button class="btn btn-success">Salvar</button>
    <a href="{{ route('centros.index') }}" class="btn btn-primary">Cancelar</a>
</form>
@endsection
