@extends('layouts.menu')

@section('content')
<div>
    <h1>Cadastrar Tipo de Serviço</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erros encontrados:</strong>
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tipo-servicos.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="conta_contabil">Conta Contábil</label>
            <input type="text" name="conta_contabil" id="conta_contabil" list="lista_contas" class="form-control" required>
            <datalist id="lista_contas">
                @foreach ($contas as $conta)
                    <option value="{{ $conta->codigo }} - {{ $conta->descricao }}">
                @endforeach
            </datalist>
        </div>

        <input type="hidden" name="conta_contabil_id" id="conta_contabil_id" />

        <div class="form-group mt-3">
            <label for="nome">Nome do Tipo de Serviço</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome') }}" required>
        </div>

        <br>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('tipo-servicos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const contas = @json($contas);

    document.getElementById('conta_contabil').addEventListener('input', function () {
        const input = this.value.trim();
        const selecionada = contas.find(c => (c.codigo + ' - ' + c.descricao) === input);
        document.getElementById('conta_contabil_id').value = selecionada ? selecionada.id : '';
    });
</script>
@endpush
