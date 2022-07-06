<?php

namespace App\Http\Controllers;

use App\Models\Dollar;
use Illuminate\Http\Request;

class DollarController extends Controller
{
    public function index()
    {
        return Dollar::find(1);
    }

    public function update()
    {
        $cb = 0;
        $date = '';
        
        $currencies = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp");
        
        foreach ($currencies->Valute as $currency) {
            if ($currency["ID"] == 'R01235') {
                $cb = round(str_replace(',','.',$currency->Value), 2);
            }
        }

        $date = date("Y-m-d", strtotime($currencies["Date"]));

        if($cb && $cb > 0) {
            $dollar = Dollar::find(1);
            $dollar->cb = $cb;
            $dollar->kurs = round(str_replace(',','.',$cb + ($cb / 100 * $dollar->additional)), 2);
            $dollar->date = $date;
            $dollar->save();
        }
    }
}
