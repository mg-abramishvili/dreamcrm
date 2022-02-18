<?php

namespace App\Http\Controllers;

use App\Models\CatalogItem;
use Illuminate\Http\Request;

class CatalogItemController extends Controller
{
    public function index()
    {
        return CatalogItem::orderBy('price', 'asc')->get();
    }

    public function indexByBox($id)
    {
        return CatalogItem::whereRelation('boxes', 'catalog_box_id', $id)->with('stockItems')->orderBy('price', 'asc')->get();
    }

    public function item($id)
    {
        return CatalogItem::with('boxes', 'category', 'stockItems')->find($id);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'boxes' => 'required',
        ];

        $this->validate($request, $rules);

        $item = new CatalogItem();
        $item->name = $request->name;
        $item->price = $request->price;
        $item->category_id = $request->category_id;
        $item->save();
        $item->boxes()->attach($request->boxes, ['catalog_item_id' => $item->id]);
        $item->stockItems()->attach($request->stock_items, ['catalog_item_id' => $item->id]);
    }

    public function update($id, Request $request)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'boxes' => 'required',
        ];

        $this->validate($request, $rules);

        $item = CatalogItem::find($id);
        $item->name = $request->name;
        $item->price = $request->price;
        $item->category_id = $request->category_id;
        $item->save();
        $item->boxes()->detach();
        $item->boxes()->attach($request->boxes, ['catalog_item_id' => $item->id]);
        $item->stockItems()->detach();
        $item->stockItems()->attach($request->stock_items, ['catalog_item_id' => $item->id]);
    }

    public function delete($id)
    {
        $item = CatalogItem::find($id);

        $item->boxes()->detach();
        $item->stockItems()->detach();
        
        $item->delete();
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
            $elements = CatalogItem::get();
            foreach ($elements as $element) {
                $element->price = ceil((($element->pre_usd * $kurs) + $element->pre_rub) / 50) * 50;
                $element->save();
            }
        }        
    }
}
