<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>КП</title>

    <style>
        @font-face {
            font-family: 'Roboto';
            src: url("{{ storage_path('fonts/Roboto-Regular.ttf') }}") format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Roboto';
            src: url("{{ storage_path('fonts/Roboto-Bold.ttf') }}") format("truetype");
            font-weight: 700;
            font-style: normal;
        }

        html,body {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table td,
        .table th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>
<body>
    <h1>{{ $offer->id }}</h1>

    <table class="table">
        <tbody>
            @foreach($offer->elements as $element)
                <tr>
                    <td>{{ $element->name }}</td>
                    <td>{{ $element->pivot->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>