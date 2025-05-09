<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contas Contábeis</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Relatório de Contas Contábeis</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contas as $conta)
                <tr>
                    <td>{{ $conta->codigo }}</td>
                    <td>{{ $conta->descricao }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
