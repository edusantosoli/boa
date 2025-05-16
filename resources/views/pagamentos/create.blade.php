@extends('layouts.menu')

@section('content')
<div class="container">
    <h1>Novo Pagamento</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pagamentos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Centro de Custo:</label>
            <select name="saldos_contabeis_id" class="form-select" required>
                <option value="">-- Selecione um Centro de Custo --</option>
                @foreach ($saldos as $saldo)
                    <option value="{{ $saldo->id }}">
                        {{ $saldo->centroDeCusto->codigo ?? '-' }} - {{ $saldo->centroDeCusto->descricao ?? '-' }} |
                        {{ $saldo->contaContabil->codigo ?? '-' }} - {{ $saldo->contaContabil->descricao ?? '-' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tipo_servico_id">Tipo de Serviço:</label>
            <select name="tipo_servico_id" class="form-select" required>
                <option value="">-- Selecione --</option>
                @foreach($tiposServicos as $tipo)
                    <option value="{{ $tipo->id }}">
                        {{ $tipo->descricao }} | Conta: {{ $tipo->contaContabil->codigo ?? '' }} - {{ $tipo->contaContabil->descricao ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Valor:</label>
            <input type="number" name="valor" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>Data do Pagamento:</label>
            <input type="date" name="data_pagamento" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>CP:</label>
            <input type="text" name="cp" class="form-control" maxlength="10" value="{{ old('cp', $pagamento->cp ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Fornecedor:</label>
            <input type="text" name="fornecedor" class="form-control" maxlength="255" value="{{ old('fornecedor', $pagamento->fornecedor ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Nota Fiscal:</label>
            <input type="text" name="notafiscal" class="form-control" maxlength="50" value="{{ old('notafiscal', $pagamento->notafiscal ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Data de Vencimento:</label>
            <input type="date" name="data_vencimento" class="form-control" value="{{ old('data_vencimento', isset($pagamento->data_vencimento) ? \Carbon\Carbon::parse($pagamento->vencimento)->format('Y-m-d') : '') }}">
        </div>

        <div class="mb-3">
            <label>Valor Original:</label>
            <input type="number" name="valor_original" class="form-control" step="0.01" value="{{ old('valor_original', $pagamento->valor_original ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Descricao:</label>
            <input type="text" name="descricao" class="form-control" maxlength="255" value="{{ old('descricao', $pagamento->descricao ?? '') }}">
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('pagamentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
