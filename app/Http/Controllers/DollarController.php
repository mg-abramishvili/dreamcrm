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
        $kurs = 0;
        
        $currencies = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp");
        
        foreach ($currencies->Valute as $currency) {
            if ($currency["ID"] == 'R01235') {
                $kurs = round(str_replace(',','.',$currency->Value), 2);
            }
        }

        if($kurs && $kurs > 0) {
            $dollar = Dollar::find(1);
            $dollar->kurs = $kurs;
            $dollar->save();
        }
    }
}
