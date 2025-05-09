<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Centros de Custo</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h1>Lista de Centros de Custo</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Criado em</th>
            </tr>
        </thead>
        <tbody>
            @foreach($centros as $centro)
            <tr>
                <td>{{ $centro->id }}</td>
                <td>{{ $centro->nome }}</td>
                <td>{{ $centro->descricao }}</td>
                <td>{{ $centro->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
