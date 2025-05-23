@extends('layouts.menu')

@section('content')
<div>
    <h1>Cadastrar Tipo de Serviço</h1>

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

    <form action="{{ route('tipo-servicos.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="conta_contabil_id">Conta Contábil</label>
            <select name="conta_contabil_id" class="form-control" required>
                <option value="">-- Selecione uma conta contábil --</option>
                @foreach ($contas as $conta)
                <option value="{{ $conta->id }}">{{ $conta->codigo }} - {{ $conta->descricao }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="nome">Nome do Tipo de Serviço</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
        </div>

        

        <br>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('tipo-servicos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
