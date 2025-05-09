@extends('layouts.menu')

@section('content')
<h2>Centros de Custo</h2><br>



@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
   <div class="mb-3">
    <a href="{{ route('centros.create') }}" class="btn btn-primary">Novo Centro de Custo</a>
    <a href="{{ route('centros.export.excel') }}"class="btn btn-primary">Exportar para Excel</a>
    <a href="{{ route('centros.export.pdf') }}"class="btn btn-primary">Exportar para PDF</a>
   </div>
    <thead>
        <tr>
            <th>Codigo</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($centros as $centro)
        <tr>
            <td>{{ $centro->codigo }}</td>
            <td>{{ $centro->descricao }}</td>
            <td>
                <a href="{{ route('centros.edit', $centro) }}"class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('centros.destroy', $centro) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Confirmar a exclusão?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
