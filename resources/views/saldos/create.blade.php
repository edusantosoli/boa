@extends("layouts.menu")

@section('content')

<div class="container">
    <h1> Cadastrar saldos Contabeis</h1>
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

<form action="{{ route('saldos.store') }}" method="POST">
    @csrf
 <div class="form-group">
        <label>Conta Contábil</label>
        <select name="conta_contabil_id" class="form-control" required>
            <option value="">-- Selecione uma conta contabil</option>
            @foreach ($contas as $conta)
                <option value="{{ $conta->id }}">{{ $conta->codigo }} - {{ $conta->descricao }}</option>
            @endforeach
        </select>
    <div>
    <div class="form-group">
        <label>Centro de Custo:</label>
        <select name="centro_de_custo_id" class="form-control" required>
            @foreach ($centros as $centro)
                <option value="{{ $centro->id }}">{{ $centro->codigo }} - {{ $centro->descricao }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Ano:</label>
        <input type="number" name="ano" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Valor (R$):</label>
        <input type="number" name="valor" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Salvar</button>
</form>
</div>


@endsection
