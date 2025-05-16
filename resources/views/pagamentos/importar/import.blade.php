@extends('layouts.menu')

@section('content')
<div class="container">
    <h1>Importar Pagamentos via Excel</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pagamentos.importar') }}" method="POST" enctype="multipart/form-data">
        @csrf {{-- <- ESSENCIAL para evitar erro 419 --}}
        
        <div class="mb-3">
            <label for="arquivo">Arquivo Excel:</label>
            <input type="file" name="arquivo" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Importar</button>
    </form>
</div>
@endsection
