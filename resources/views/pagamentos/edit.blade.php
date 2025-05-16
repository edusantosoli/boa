@extends('layouts.menu')

@section('content')
<div class="container">
    <h1>Editar Pagamento</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('pagamentos.update', $pagamento->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Centro de Custo / Conta Contábil:</label>
            <select name="saldos_contabeis_id" class="form-select" required>
                @foreach ($saldos as $saldo)
                    <option value="{{ $saldo->id }}"
                        {{ $pagamento->saldos_contabeis_id == $saldo->id ? 'selected' : '' }}>
                        {{ $saldo->centroDeCusto->codigo ?? '-' }} - {{ $saldo->centroDeCusto->descricao ?? '-' }}
                        /
                        {{ $saldo->contaContabil->codigo ?? '-' }} - {{ $saldo->contaContabil->descricao ?? '-' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Valor do Pagamento:</label>
            <input type="number" step="0.01" name="valor" class="form-control" value="{{ $pagamento->valor }}" required>
        </div>

        <div class="mb-3">
            <label>Data do Pagamento:</label>
            <input type="date" name="data_pagamento" class="form-control" value="{{ $pagamento->data_pagamento }}" required>
        </div>

        <div class="mb-3">
            <label>CP:</label>
            <input type="text" name="cp" class="form-control" maxlength="10" value="{{ $pagamento->cp }}">
        </div>

        <div class="mb-3">
            <label>Pagado:</label>
            <input type="text" name="pagado" class="form-control" maxlength="255" value="{{ $pagamento->pagado }}">
        </div>

        <div class="mb-3">
            <label>Nota Fiscal:</label>
            <input type="text" name="notafiscal" class="form-control" maxlength="50" value="{{ $pagamento->notafiscal }}">
        </div>

        <div class="mb-3">
            <label>Vencimento:</label>
            <input type="date" name="vencimento" class="form-control" value="{{ $pagamento->vencimento }}">
        </div>

        <div class="mb-3">
            <label>Valor Original:</label>
            <input type="number" step="0.01" name="valor_original" class="form-control" value="{{ $pagamento->valor_original }}">
        </div>

        <div class="mb-3">
            <label>Glosa:</label>
            <input type="text" name="glosa" class="form-control" maxlength="255" value="{{ $pagamento->glosa }}">
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('pagamentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
