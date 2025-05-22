@extends('layouts.menu')

@section('content')
<div>
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

        <div class="form-group">
            <label>Centro de Custo:</label>
            <select name="saldos_contabeis_id" id="saldos_contabeis_id" class="form-control" required>
                <option value="">-- Selecione um Centro de Custo --

                </option>
                @foreach ($saldos as $saldo)
                    <option value="{{ $saldo->id }}" data-conta="{{ $saldo->contaContabil->id }}">
                        {{ $saldo->centroDeCusto->codigo ?? '-' }} - {{ $saldo->centroDeCusto->descricao ?? '-' }} |
                        {{ $saldo->contaContabil->codigo ?? '-' }} - {{ $saldo->contaContabil->descricao ?? '-' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Tipo de Serviço:</label>
            <select name="tipo_servico_id" id="tipo_servico_id" class="form-control">
                <option value="">-- Selecione o Tipo de Serviço --</option>
            </select>
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
            <label>Data da Baixa:</label>
            <input type="date" name="data_pagamento" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Valor Pago:</label>
            <input type="number" name="valor" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>Valor Original:</label>
            <input type="number" name="valor_original" class="form-control" step="0.01" value="{{ old('valor_original', $pagamento->valor_Original ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Descricao:</label>
            <input type="text" name="descricao" class="form-control" maxlength="255" value="{{ old('descricao', $pagamento->descricao ?? '') }}">
        </div>
        
        <button type="submit" class="btn btn-success">Salvar</button>
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao buscar tipos de serviço.');
                    }
                    return response.json();
                })
                .then(data => {
                    tipoServicoSelect.innerHTML = '<option value="">-- Selecione o Tipo de Serviço --</option>';
                    data.forEach(tipo => {
                        const option = document.createElement('option');
                        option.value = tipo.id;
                        option.textContent = tipo.nome;
                        tipoServicoSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    tipoServicoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                    console.error(error);
                });
        } else {
            tipoServicoSelect.innerHTML = '<option value="">-- Selecione o Tipo de Serviço --</option>';
        }
    });
</script>
@endsection
