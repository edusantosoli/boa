@extends('layouts.menu')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Lançamento</h1>

    <form action="{{ route('lancamentos.update', $lancamento->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Centro de Custo</label>
            <select name="centro_de_custo_id" class="form-select" required>
                @foreach ($centros as $centro)
                    <option value="{{ $centro->id }}" {{ $centro->id == $lancamento->centro_de_custo_id ? 'selected' : '' }}>
                        {{ $centro->codigo }} - {{ $centro->descricao }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Programa</label>
            <input type="text" name="programa" class="form-control" value="{{ $lancamento->programa }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pagando a</label>
            <input type="text" name="pagando_a" class="form-control" value="{{ $lancamento->pagando_a }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nota Fiscal</label>
            <input type="text" name="nota_fiscal" class="form-control" value="{{ $lancamento->nota_fiscal }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Data de Vencimento</label>
            <input type="date" name="data_vencimento" class="form-control" value="{{ $lancamento->data_vencimento }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Data da Baixa</label>
            <input type="date" name="data_baixa" class="form-control" value="{{ $lancamento->data_baixa }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Valor Original</label>
            <input type="number" step="0.01" name="valor_original" class="form-control" value="{{ $lancamento->valor_original }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Valor Pago</label>
            <input type="number" step="0.01" name="valor_pago" class="form-control" value="{{ $lancamento->valor_pago }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Glosa</label>
            <input type="number" step="0.01" name="glosa" class="form-control" value="{{ $lancamento->glosa }}">
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('lancamentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
    const centros = @json($centros);
    const selectCentro = document.getElementById('centro_de_custo_id');
    const inputPrograma = document.getElementById('programa');

    function atualizarPrograma() {
        const selecionado = centros.find(c => c.id == selectCentro.value);
        inputPrograma.value = selecionado ? selecionado.descricao : '';
    }

    // Atualiza ao abrir a página
    atualizarPrograma();

    // Atualiza ao trocar o select
    selectCentro.addEventListener('change', atualizarPrograma);
</script>

@endsection
