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
            line-height: 1;
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
            background: url('{{ public_path('img/offer-bg.png') }}');
            background-size: cover;
            background-position: center center;
        }

        .table-top-row {
            margin-bottom: 15px;
        }
        .table-top-cell-icon {
            width: 20px;
            height: auto;
            display: inline-block;
            vertical-align: middle;
            margin-right: 6px;
        }
        .table-top-cell-text {
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
            margin-top: -2px;
            font-size: 13px;
        }
        .rank {
            display: inline-block;
            line-height: 1;
            margin: 0;
            width: 100%;
        }
        .name {
            margin-top: 0;
            margin-bottom: 10px;
            line-height: 1;
            display: inline-block;
            width: 100%;
            font-size: 28px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="page">
        <table class="table" style="padding-top: 70px; margin-bottom: 10px;">
            <tbody>
                <tr>
                    <td style="width: 50%; padding-left: 40px; border: 0; vertical-align:top;">
                        <span class="rank">Менеджер отдела продаж</span>
                        <br>
                        @foreach($offer->calculations as $calculation)
                            @if($loop->first)
                                <span class="name">
                                    {{ $calculation->user->name }}
                                </span>
                                <br>
                                <span>+7 999 000-00-00</span>
                                <br>
                                <span>{{ $calculation->user->email }}</span>
                            @endif
                        @endforeach
                    </td>
                    <td style="width: 50%; border: 0; vertical-align:top; position: relative;">
                        <img src="{{ public_path('img/logo-h.png') }}" style="width: auto; height: 60px; position: absolute; top: -35px; right: 60px;"/>
                        
                        <div class="table-top-row">
                            <img src="{{ public_path('img/envelope-fill.png') }}" class="table-top-cell-icon" />
                            <span class="table-top-cell-text">8 800 301-09-39</span>
                        </div>

                        <div class="table-top-row">
                            <img src="{{ public_path('img/envelope-fill.png') }}" class="table-top-cell-icon" />
                            <span class="table-top-cell-text">mg@dreamapp.ru | info@dreamapp.ru</span>
                        </div>

                        <div class="table-top-row">
                            <img src="{{ public_path('img/envelope-fill.png') }}" class="table-top-cell-icon" />
                            <span class="table-top-cell-text">
                                г. Москва, ул. Марии Поливановой, д. 9<br>
                                г. Санкт-Петербург, шоссе Революции, д. 69, офис 4
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <span style="display:block;margin-bottom: 20px; text-align: center; font-size: 12px; color: #888;">www.dreamapp.ru | Интерактивная автоматизация | Профессиональное сенсорное оборудование</span>
        
        <span style="display:block;margin-bottom: 20px; text-align: center; font-size: 22px; font-weight: bold;">
            Коммерческое предложение №{{ $offer->id }} от {{ Carbon\Carbon::parse($offer->created_at)->format('d.m.Y') }}
        </span>

        <table class="table" style="width: 90%; margin: 0 auto;">
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