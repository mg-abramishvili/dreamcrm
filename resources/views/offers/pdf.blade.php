<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>КП</title>

    <style>
        @page { margin: 0px; }
        body { margin: 0px; position: relative; color: #000; }

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
        @font-face {
            font-family: 'Anime Ace';
            src: url("{{ storage_path('fonts/anime-ace-v05.ttf') }}") format("truetype");
            font-weight: 400;
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
            background: url(@include('offers.offer-bg'));
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
            font-family: 'Anime Ace', sans-serif;
        }
        .name {
            margin-top: 0;
            margin-bottom: 10px;
            line-height: 1;
            display: inline-block;
            width: 100%;
            font-family: 'Anime Ace', sans-serif;
            font-size: 28px;
        }
        .slogan {
            display: block;
            margin-bottom: 20px;
            text-align: center;
            font-size: 13px;
            font-family: 'Anime Ace', sans-serif;
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
                        <img src="@include('offers.logo-h')" style="width: auto; height: 60px; position: absolute; top: -35px; right: 60px;"/>
                        
                        <div class="table-top-row">
                            <img src="@include('offers.envelope')" class="table-top-cell-icon" />
                            <span class="table-top-cell-text">8 800 301-09-39</span>
                        </div>

                        <div class="table-top-row">
                            <img src="@include('offers.envelope')" class="table-top-cell-icon" />
                            <span class="table-top-cell-text">mg@dreamapp.ru | info@dreamapp.ru</span>
                        </div>

                        <div class="table-top-row">
                            <img src="@include('offers.envelope')" class="table-top-cell-icon" />
                            <span class="table-top-cell-text">
                                г. Москва, ул. Марии Поливановой, д. 9<br>
                                г. Санкт-Петербург, шоссе Революции, д. 69, офис 4
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <span class="slogan">Интерактивная автоматизация | Профессиональное сенсорное оборудование</span>
        
        <span style="display:block;margin-bottom: 20px; text-align: center; font-size: 22px; font-weight: bold;">
            Коммерческое предложение №{{ $offer->id }} от {{ Carbon\Carbon::parse($offer->created_at)->format('d.m.Y') }}
        </span>

        <table class="table" style="width: 90%; margin: 0 auto;">
            <thead>
                <tr>
                    <th style="width: 63%;">Характеристика</th>
                    <th style="width: 10%; text-align: center;">Кол-во</th>
                    <th style="width: 13.5%; text-align: center;">Цена</th>
                    <th style="width: 13.5%; text-align: center;">Стоимость</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offer->calculations as $calculation)
                    <td>
                        @foreach($calculation->boxes as $box)
                            <span style="font-weight: bold">Корпус:</span>
                            <span>{{ $box->name }}</span>
                            <br>
                            <br>

                            @if($box->description)
                                {{ $box->description }}
                                <br>
                                <br>
                            @endif
                        @endforeach

                        @foreach($calculation->catalogItems as $item)
                            @if($item->price > 0)
                                <span style="font-weight: bold">{{ $item->category->name }}:</span>
                                <span>{{ $item->name }}</span>
                                <br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        {{ $calculation->quantity }} шт.
                    </td>
                    <td style="text-align: center;">
                        @php
                            echo number_format($calculation->price,0,","," ");
                        @endphp
                        ₽
                    </td>
                    <td style="text-align: center;">
                        @php
                            echo number_format($calculation->price * $calculation->quantity,0,","," ");
                        @endphp
                        ₽
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