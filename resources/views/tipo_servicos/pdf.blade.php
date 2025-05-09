<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tipo de Serviços</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h2>Lista de Tipo de Serviços</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Conta Contábil</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados as $tipo)
                <tr>
                    <td>{{ $tipo->nome }}</td>
                    <td>{{ $tipo->contaContabil->descricao ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
