@extends('layouts.menu')

@section('content')
<div class="container">
    <h1>Pagamentos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('pagamentos.create') }}" class="btn btn-primary mb-3">Novo Pagamento</a>

    <form action="{{ route('pagamentos.importar') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <input type="file" name="arquivo" class="form-control" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Importar Excel</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>CP</th>
                <th>Centro de Custo</th>
                <th>Conta Contábil</th>
                <th>Valor</th>
                <th>Data de Pagamento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pagamentos as $pagamento)
                <tr>
                    <td>{{ $pagamento->cp }}</td>
                    
                    {{-- Centro de Custo --}}
                    <td>
                        {{ $pagamento->saldoContabil->centroDeCusto->codigo ?? '-' }} -
                        {{ $pagamento->saldoContabil->centroDeCusto->descricao ?? '-' }}
                    </td>

                    {{-- Conta Contábil --}}
                    <td>
                        {{ $pagamento->saldoContabil->contaContabil->codigo ?? '-' }} -
                        {{ $pagamento->saldoContabil->contaContabil->descricao ?? '-' }}
                    </td>

                    <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }}</td>

                    <td>
                        <a href="{{ route('pagamentos.edit', $pagamento->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('pagamentos.destroy', $pagamento->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $pagamentos->links() }}
</div>
@endsection
