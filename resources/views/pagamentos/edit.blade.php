@extends('layouts.menu')

@section('content')
<div class="container">
    <h1>Editar Pagamento</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pagamentos.update', $pagamento->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Centro de Custo:</label>
            <select name="saldos_contabeis_id" id="saldos_contabeis_id" class="form-select" required>
                <option value="">-- Selecione um Centro de Custo --</option>
                @foreach ($saldos as $saldo)
                    <option value="{{ $saldo->id }}" data-conta="{{ $saldo->contaContabil->id }}"
                        {{ $pagamento->saldos_contabeis_id == $saldo->id ? 'selected' : '' }}>
                        {{ $saldo->centroDeCusto->codigo ?? '-' }} - {{ $saldo->centroDeCusto->descricao ?? '-' }} |
                        {{ $saldo->contaContabil->codigo ?? '-' }} - {{ $saldo->contaContabil->descricao ?? '-' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo de Serviço:</label>
            <select name="tipo_servico_id" id="tipo_servico_id" class="form-select">
                <option value="">-- Selecione o Tipo de Serviço --</option>
                @foreach ($tipos_servico as $tipo)
                    <option value="{{ $tipo->id }}" {{ $pagamento->tipo_servico_id == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nome ?? $tipo->descricao }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Valor:</label>
            <input type="number" name="valor" class="form-control" step="0.01" value="{{ old('valor', $pagamento->valor) }}" required>
        </div>

        <div class="mb-3">
            <label>Data do Pagamento:</label>
            <input type="date" name="data_pagamento" class="form-control" value="{{ old('data_pagamento', optional($pagamento->data_pagamento)->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label>CP:</label>
            <input type="text" name="cp" class="form-control" maxlength="10" value="{{ old('cp', $pagamento->cp) }}">
        </div>

        <div class="mb-3">
            <label>Fornecedor:</label>
            <input type="text" name="fornecedor" class="form-control" maxlength="255" value="{{ old('fornecedor', $pagamento->fornecedor) }}">
        </div>

        <div class="mb-3">
            <label>Nota Fiscal:</label>
            <input type="text" name="notafiscal" class="form-control" maxlength="50" value="{{ old('notafiscal', $pagamento->notafiscal) }}">
        </div>

        <div class="mb-3">
            <label>Data de Vencimento:</label>
            <input type="date" name="data_vencimento" class="form-control" value="{{ old('data_vencimento', optional($pagamento->data_vencimento)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label>Valor Original:</label>
            <input type="number" name="valor_Original" class="form-control" step="0.01" value="{{ old('valor_Original', $pagamento->valor_Original) }}">
        </div>

        <div class="mb-3">
            <label>Descricao:</label>
            <input type="text" name="descricao" class="form-control" maxlength="255" value="{{ old('descricao', $pagamento->descricao) }}">
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('pagamentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    document.getElementById('saldos_contabeis_id').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const contaId = selectedOption.getAttribute('data-conta');
        const tipoServicoSelect = document.getElementById('tipo_servico_id');

        tipoServicoSelect.innerHTML = '<option value="">Carregando...</option>';

        if (contaId) {
            fetch(`/tipos-servico/por-conta/${contaId}`)
                .then(response => response.json())
                .then(data => {
                    tipoServicoSelect.innerHTML = '<option value="">-- Selecione o Tipo de Serviço --</option>';
                    data.forEach(tipo => {
                        const option = document.createElement('option');
                        option.value = tipo.id;
                        option.textContent = tipo.nome ?? tipo.nome;
                        tipoServicoSelect.appendChild(option);
                    });
                });
        } else {
            tipoServicoSelect.innerHTML = '<option value="">-- Selecione o Tipo de Serviço --</option>';
        }
    });
</script>
@endsection