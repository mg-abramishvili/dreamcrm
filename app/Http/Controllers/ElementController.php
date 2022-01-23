<?php

namespace App\Http\Controllers;

use App\Models\Element;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    public function index()
    {
        return Element::orderBy('price', 'asc')->get();
    }

    public function indexByBox($id)
    {
        return Element::whereRelation('boxes', 'box_id', $id)->get();
    }

    public function element($id)
    {
        return Element::where('id', $id)->with('boxes', 'category')->first();
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'pre_rub' => 'required|numeric',
            'pre_usd' => 'required|numeric',
            'price' => 'required|numeric',
            'category' => 'required',
            'boxes' => 'required',
        ];

        $this->validate($request, $rules);

        $element = new Element();
        $element->name = $request->name;
        $element->category_id = $request->category;
        $element->pre_rub = $request->pre_rub;
        $element->pre_usd = $request->pre_usd;
        $element->price = $request->price;
        $element->save();
        $element->boxes()->attach($request->boxes, ['element_id' => $element->id]);
    }

    public function updatePrices()
    {
        $kurs = 0;
        $currencies = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp");
        foreach ($currencies->Valute as $currency) {
            if ($currency["ID"] == 'R01235') {
                $kurs = round(str_replace(',','.',$currency->Value), 2);
            }
        }

        if($kurs && $kurs > 0) {
            $elements = Element::get();
            foreach ($elements as $element) {
                $element->price = ceil((($element->pre_usd * $kurs) + $element->pre_rub) / 50) * 50;
                $element->save();
            }
        }        
    }
}
