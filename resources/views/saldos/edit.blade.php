@extends('layouts.menu')

@section('content')
<div>
    <h1 class="mb-4">Editar Saldo Contábil</h1>
    <br>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('saldos.update', $saldo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="conta_contabil_id" class="form-label">Conta Contábil</label>
            <select name="conta_contabil_id" id="conta_contabil_id" class="form-control" required>
                @foreach ($contas as $conta)
                    <option value="{{ $conta->id }}" {{ $saldo->conta_contabil_id == $conta->id ? 'selected' : '' }}>
                        {{ $conta->codigo }} - {{ $conta->descricao }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="centro_de_custo_id" class="form-label">Centro de Custo</label>
            <select name="centro_de_custo_id" id="centro_de_custo_id" class="form-control" required>
                @foreach ($centros as $centro)
                    <option value="{{ $centro->id }}" {{ $saldo->centro_de_custo_id == $centro->id ? 'selected' : '' }}>
                        {{ $centro->codigo }} - {{ $centro->descricao }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ano" class="form-label">Ano</label>
            <input type="number" name="ano" class="form-control" value="{{ $saldo->ano }}" required>
        </div>

        <div class="mb-3">
            <label for="saldo_original" class="form-label">Saldo Original (R$)</label>
            <input type="number" id="saldo_original" name="saldo_original" class="form-control" value="{{ $saldo->saldo }}" readonly>
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor (R$)</label>
            <input type="number" id="valor" name="valor" class="form-control" value="{{ $saldo->valor }}" required>
        </div>

        <div class="mb-3">
            <label for="saldo_Atualizado" class="form-label">Saldo Atualizado (R$)</label>
            <input type="text" id="saldo_atualizado" name="saldo_atualizado" class="form-control" value="{{ $saldo->saldo }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('saldos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const valorInput = document.querySelector('#valor');
        const saldoOriginalInput = document.querySelector('#saldo_original');
        const saldoAtualizadoInput = document.querySelector('#saldo_atualizado');

        function atualizarSaldoAtualizado() {
            const valor = parseFloat(valorInput.value) || 0;
            const saldoOriginal = parseFloat(saldoOriginalInput.value) || 0;
            const saldoAtualizado = saldoOriginal + valor;
            saldoAtualizadoInput.value = saldoAtualizado.toFixed(2);
        }

        valorInput.addEventListener('input', atualizarSaldoAtualizado);
    });
</script>
@endsection
