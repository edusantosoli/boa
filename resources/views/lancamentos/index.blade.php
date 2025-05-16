@extends('layouts.menu')

@section('content')
<div class="container">
    <h1 class="mb-4">Lançamentos Contábeis</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('lancamentos.create') }}" class="btn btn-primary mb-3">Novo Lançamento</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Centro de Custo</th>
                <th>Programa</th>
                <th>Pagando a</th>
                <th>Nota Fiscal</th>
                <th>Vencimento</th>
                <th>Baixa</th>
                <th>Valor Original</th>
                <th>Valor Pago</th>
                <th>Glosa</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lancamentos as $lancamento)
                <tr>
                    <td>{{ $lancamento->centroDeCusto->codigo ?? '-' }} - {{ $lancamento->centroDeCusto->descricao ?? '-' }}</td>
                    <td>{{ $lancamento->programa }}</td>
                    <td>{{ $lancamento->pagando_a }}</td>
                    <td>{{ $lancamento->nota_fiscal }}</td>
                    <td>{{ \Carbon\Carbon::parse($lancamento->data_vencimento)->format('d/m/Y') }}</td>
                    <td>{{ $lancamento->data_baixa ? \Carbon\Carbon::parse($lancamento->data_baixa)->format('d/m/Y') : '-' }}</td>
                    <td>R$ {{ number_format($lancamento->valor_original, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($lancamento->valor_pago, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($lancamento->glosa, 2, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('lancamentos.edit', $lancamento->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('lancamentos.destroy', $lancamento->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Deseja excluir este lançamento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10">Nenhum lançamento encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $lancamentos->links() }}
</div>
@endsection