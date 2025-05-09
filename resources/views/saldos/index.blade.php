@extends('layouts.menu')

@section('content')
<div>
    <h1 class="mb-4">Saldos Contábeis</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('saldos.create') }}" class="btn btn-primary mb-3">Novo Saldo</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Conta Contábil</th>
                <th>Centro de Custo</th>
                <th>Ano</th>
                <th>Valor (R$)</th>
                <th>Saldo Atual (R$)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($saldos as $saldo)
                <tr>
                    <td>
                        {{ $saldo->contaContabil->codigo ?? '-' }} - {{ $saldo->contaContabil->descricao ?? '-' }}
                    </td>
                    <td>
                        {{ $saldo->centroDeCusto->codigo ?? '-' }} - {{ $saldo->centroDeCusto->descricao ?? '-' }}
                    </td>
                    <td>{{ $saldo->ano }}</td>
                    <td>R$ {{ number_format($saldo->valor, 2, ',', '.') }}</td>

                    @php
                        $chave = $saldo->conta_contabil_id . '-' . $saldo->centro_de_custo_id . '-' . $saldo->ano;
                        $total = $saldosTotais[$chave]->total ?? 0;
                    @endphp
                    <td>R$ {{ number_format($total, 2, ',', '.') }}</td>

                    <td>
                        <a href="{{ route('saldos.edit', $saldo->id) }}" class="btn btn-sm btn-warning">Editar</a>

                        <form action="{{ route('saldos.destroy', $saldo->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir este saldo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhum saldo cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $saldos->links() }}
</div>
@endsection
