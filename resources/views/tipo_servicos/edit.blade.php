@extends('layouts.menu')

@section('content')
<div class="container">
    <h1>Editar Tipo de Serviço</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erros encontrados:</strong>
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tipo-servicos.update', $tipo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nome">Nome do Tipo de Serviço</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $tipo->nome) }}" required>
        </div>

        <div class="form-group">
            <label for="conta_contabil_id">Conta Contábil</label>
            <select name="conta_contabil_id" class="form-control" required>
                <option value="">-- Selecione uma conta contábil --</option>
                @foreach ($contas as $conta)
                    <option value="{{ $conta->id }}" {{ (old('conta_contabil_id', $tipo->conta_contabil_id) == $conta->id) ? 'selected' : '' }}>
                        {{ $conta->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('tipo-servicos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
