@extends('layouts.menu')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Importar Pagamentos</h2>

    {{-- Mensagens de sucesso --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Mensagens de erro --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Erros ao importar:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulário de upload --}}
    <form action="{{ route('pagamentos.importar') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label for="arquivo"><strong>Arquivo Excel:</strong></label>
            <input type="file" name="arquivo" id="arquivo" class="form-control" required accept=".xlsx,.xls">
            <small class="form-text text-muted">O arquivo deve conter as colunas: C.P, Partida, Programa, Pagado a, Nota Fiscal, Data Vencimento, Data Pagamento, Valor Original, Valor Pago, Descrição.</small>
        </div>

        <button type="submit" class="btn btn-primary">Importar</button>
        <a href="{{ route('pagamentos.index') }}" class="btn btn-secondary ms-2">Voltar</a>
    </form>
</div>
@endsection