@extends('layouts.menu')

@section('content')

    <h1>Tipos de Serviços</h1>
    <br>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    

    <table class="table table-bordered">
        <div class="mb-3">
            <a href="{{ route('tipo-servicos.create') }}" class="btn btn-primary">Novo Tipo de Serviço</a>
            <a href="{{ route('tipo-servicos.export.excel') }}" class="btn btn-success">Exportar Excel</a>
            <a href="{{ route('tipo-servicos.export.pdf') }}" class="btn btn-danger">Exportar PDF</a>
        </div>

        <thead>
            <tr>
                <th>Nome</th>
                <th>Conta Contábil</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tipos as $tipo)
                <tr>
                    <td>{{ $tipo->nome }}</td>
                    <td>
                        @if ($tipo->contaContabil)
                            {{ $tipo->contaContabil->codigo }} - {{ $tipo->contaContabil->descricao }}
                        @else
                            <em>Não vinculada</em>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tipo-servicos.edit', $tipo->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('tipo-servicos.destroy', $tipo->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este tipo de serviço?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Nenhum tipo de serviço cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection
