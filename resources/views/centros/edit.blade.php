@extends('layouts.menu')

@section('content')
<h2>Editar Centro de Custo</h2>

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

<form action="{{ route('centros.update', $centro) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="codigo">Codigo:</label>
        <input type="text" name="codigo" class="form-control" id="codigo" value="{{ old('codigo', $centro->codigo) }}" required>
    </div>

    <div class="mb-3">
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" class="form-control" value="{{$centro->descricao}}"> </input>
    </div>

    <button class="btn btn-primary" type="submit">Atualizar</button>

    <a href="{{ route('centros.index') }}" class="btn btn-primary">Cancelar</a>
</form>
@endsection
