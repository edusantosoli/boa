@extends('layouts.menu')

@section('content')
<div>
    <h1 class="mb-4">Pagamentos</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('pagamentos.create') }}" class="btn btn-primary">➕ Novo Pagamento</a>
        <a href="{{ route('pagamentos.importar.view') }}" class="btn btn-success">📁 Importar Pagamentos</a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('pagamentos.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="centro_custo_id">Centro de Custo</label>
            <select name="centro_custo_id" class="form-control">
                <option value="">Todos</option>
                @foreach ($centros_custo as $cc)
                    <option value="{{ $cc->id }}" {{ request('centro_custo_id') == $cc->id ? 'selected' : '' }}>
                        {{ $cc->codigo }} - {{ $cc->descricao }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="tipo_servico_id">Tipo de Serviço</label>
            <select name="tipo_servico_id" class="form-control">
                <option value="">Todos</option>
                @foreach ($tipos_servico as $tipo)
                    <option value="{{ $tipo->id }}" {{ request('tipo_servico_id') == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="data_inicio">De:</label>
            <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
        </div>

        <div class="col-md-2">
            <label for="data_fim">Até:</label>
            <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-secondary me-2">Filtrar</button>
            <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </form>

    {{-- Tabela --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Centro de Custo</th>
                    <th>Conta Contábil</th>
                    <th>Tipo de Serviço</th>
                    <th>CP</th>
                    <th>Fornecedor</th>
                    <th>Nota Fiscal</th>
                    <th>Vencimento</th>
                    <th>Baixa</th>
                    <th>Valor Pago</th>
                    <th>Valor Original</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagamentos as $pagamento)
                    <tr>
                        <td>
                            {{ $pagamento->saldoContabil->centroDeCusto->codigo ?? '-' }} -
                            {{ $pagamento->saldoContabil->centroDeCusto->descricao ?? '-' }}
                        </td>
                        <td>
                            {{ $pagamento->saldoContabil->contaContabil->codigo ?? '-' }} -
                            {{ $pagamento->saldoContabil->contaContabil->descricao ?? '-' }}
                        </td>
                        <td>{{ $pagamento->tipoServico->nome ?? '-' }}</td>
                        <td>{{ $pagamento->cp }}</td>
                        <td>{{ $pagamento->fornecedor }}</td>
                        <td>{{ $pagamento->notafiscal }}</td>
                        <td>{{ optional($pagamento->data_vencimento)->format('d/m/Y') }}</td>
                        <td>{{ optional($pagamento->data_pagamento)->format('d/m/Y') }}</td>
                        <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($pagamento->valor_Original, 2, ',', '.') }}</td>
                        <td>{{ $pagamento->descricao }}</td>
                        <td>
                            <a href="{{ route('pagamentos.edit', $pagamento->id) }}" class="btn btn-sm btn-warning" title="Editar">✏️</a>
                            <form action="{{ route('pagamentos.destroy', $pagamento->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Deseja excluir este pagamento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Excluir">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="12">Nenhum pagamento encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $pagamentos->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection
