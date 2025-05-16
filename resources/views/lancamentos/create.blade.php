@extends('layouts.menu')

@section('content')
<div class="container">
    <h1 class="mb-4">Novo Lançamento Contábil</h1>

    <form action="{{ route('lancamentos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="centro_de_custo_id" class="form-label">Centro de Custo</label>
            <select name="centro_de_custo_id" class="form-select" required>
                <option value="">Selecione...</option>
                @foreach ($centros as $centro)
                    <option value="{{ $centro->id }}">{{ $centro->codigo }} - {{ $centro->descricao }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="conta_contabil_id" class="form-label">Conta Contábil</label>
            <select name="conta_contabil_id" class="form-select" required>
                <option value="">Selecione...</option>
                @foreach ($contasContabeis as $conta)
                    <option value="{{ $conta->id }}">{{ $conta->codigo ?? $conta->id }} - {{ $conta->descricao ?? $conta->descricao }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="centro_de_custo_id" class="form-label">Programa</label>
            <select name="centro_de_custo_id" class="form-select" required>
                <option value="">Selecione...</option>
                @foreach ($centros as $centro)
                    <option value="{{ $centro->id }}">{{ $centro->codigo }} - {{ $centro->descricao }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Pagando a</label>
            <input type="text" name="pagando_a" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nota Fiscal</label>
            <input type="text" name="nota_fiscal" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Data de Vencimento</label>
            <input type="date" name="data_vencimento" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Data da Baixa</label>
            <input type="date" name="data_baixa" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Valor Original</label>
            <input type="number" step="0.01" name="valor_original" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Valor Pago</label>
            <input type="number" step="0.01" name="valor_pago" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Glosa</label>
            <input type="number" step="0.01" name="glosa" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('lancamentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
    const centros = @json($centros);
    const selectCentro = document.querySelector('[name="centro_de_custo_id"]');
    const inputPrograma = document.getElementById('programa');

    selectCentro.addEventListener('change', function () {
        const selecionado = centros.find(c => c.id == this.value);
        inputPrograma.value = selecionado ? selecionado.descricao : '';
    });
</script>

@endsection
