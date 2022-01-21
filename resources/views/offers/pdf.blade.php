<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>КП</title>

    <style>
        @page { margin: 0px; }
        body { margin: 0px; position: relative; }

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

        .page-break {
            page-break-after: always;
        }

        .page {
            width: 100vw;
            height: 100vh;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="page">
        <table class="table" style="margin-bottom: 50px;">
            <tbody>
                <tr>
                    <td style="width: 50%; padding-left: 50px;">
                        <span>Менеджер отдела продаж</span>
                        <br>
                        @foreach($offer->calculations as $calculation)
                            @if($loop->first)
                                <span style="margin-top:0; margin-bottom:0; font-size: 30px; font-weight: bold;">
                                    {{ $calculation->user->name }}
                                </span>
                                <br>
                                <span>{{ $calculation->user->email }}</span>
                            @endif
                        @endforeach
                    </td>
                    <td style="width: 50%;">
                        <span>8 800 301-09-39</span>

                        <br><br>

                        <span>mg@dreamapp.ru | info@dreamapp.ru</span>

                        <br><br>

                        <span>г. Москва, ул. Марии Поливановой, д. 9</span><br>
                        <span>г. Санкт-Петербург, шоссе Революции, д. 69, офис 4</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <span style="display:block;margin-bottom: 20px; text-align: center;">www.dreamapp.ru | Интерактивная автоматизация | Профессиональное сенсорное оборудование</span>
        
        <span style="display:block;margin-bottom: 20px; text-align: center; font-size: 22px; font-weight: bold;">Коммерческое предложение №{{ $offer->id }} от {{ $offer->created_at }}</span>

        <table class="table">
            <tbody>
                @foreach($offer->calculations as $calculation)
                    <td>
                        @foreach($calculation->elements as $element)
                            @if($element->price > 0)
                                <span style="font-weight: bold">{{ $element->category->name }}:</span>
                                <span>{{ $element->name }}</span>
                                <br>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        {{ $calculation->quantity }} шт.
                    </td>
                    <td>
                        0
                    </td>
                    <td>
                        0
                    </td>
                @endforeach
            </tbody>
        </table>

        <span style="position: absolute; bottom: 20px; left: 25px; font-size: 14px; font-weight: bold;">
            &copy; ООО "Тач Лаб" <br>
            Юр. адрес: Россия, 191144, г. Санкт-Петербург, ул. Новгородская, д. 13, лит. 3 <br>
            ИНН: 7842494360
        </span>
    </div>

    <div class="page-break"></div>

    page 2
</body>
</html>