
@extends('layouts.menu')
@section('content')
<h2>Contas Contábeis</h2><br>


@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <div class="mb-3">
        <a href="{{ route('contas.create') }}" class="btn btn-primary">Nova Conta</a>
        <a href="{{ route('contas.export.excel') }}" class="btn btn-success">Exportar Excel</a>
        <a href="{{ route('contas.export.pdf') }}" class="btn btn-danger">Exportar PDF</a>
    </div>
    
    <thead>
        <tr>
            <th>Código</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($contas as $conta)
        <tr>
            <td>{{ $conta->codigo }}</td>
            <td>{{ $conta->descricao }}</td>
            <td>
                <a href="{{ route('contas.edit', $conta) }}" class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('contas.destroy', $conta) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Confirmar exclusão?')">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
