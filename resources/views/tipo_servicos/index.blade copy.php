@extends('layouts.menu')

@section('content')
<div class="container">
    <h1>Cadastro de Tipos de Serviço</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('tipo-servicos.create') }}" class="btn btn-primary mb-3">Novo Tipo de Serviço</a>

    <table class="table table-bordered">
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
</div>
@endsection
