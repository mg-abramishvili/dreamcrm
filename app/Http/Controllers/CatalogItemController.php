<?php

namespace App\Http\Controllers;

use App\Models\CatalogItem;
use App\Models\Dollar;
use Illuminate\Http\Request;

class CatalogItemController extends Controller
{
    public function index()
    {
        return CatalogItem::orderBy('name', 'asc')->get();
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
        $item->pre_rub = $request->pre_rub;
        $item->pre_usd = $request->pre_usd;
        $item->category_id = $request->category_id;
        $item->save();

        $item->boxes()->sync($request->boxes);

        $stockItems = [];
        foreach($request->stock_items as $stockItem) {
            $stockItems[] = $stockItem['id'];
        }
        foreach($request->stock_items as $stockItem) {
            $stockItems[$stockItem['id']] = ['quantity' => $stockItem['quantity']];
        }
        $item->stockItems()->sync($stockItems);
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
        $item->pre_rub = $request->pre_rub;
        $item->pre_usd = $request->pre_usd;
        $item->category_id = $request->category_id;
        $item->save();
        
        $item->boxes()->sync($request->boxes);
        
        $stockItems = [];
        foreach($request->stock_items as $stockItem) {
            $stockItems[] = $stockItem['id'];
        }
        foreach($request->stock_items as $stockItem) {
            $stockItems[$stockItem['id']] = ['quantity' => $stockItem['quantity']];
        }
        $item->stockItems()->sync($stockItems);
    }

    public function delete($id)
    {
        $item = CatalogItem::find($id);

        if(count($item->calculations))
        {
            return response('Позицию нельзя удалить - она участвует в расчетах', 500);
        }

        $item->boxes()->detach();
        $item->stockItems()->detach();
        
        $item->delete();
    }

    public function updatePrices()
    {
        $kurs = Dollar::find(1)->kurs;

        if(!$kurs) { return; }

        $items = CatalogItem::all();

        foreach ($items as $item) {
            $itemPrice = 0;

            foreach($item->stockItems as $stockItem)
            {
                if($stockItem->latestBalance) {
                    $itemPrice += $stockItem->latestBalance->pre_rub * $stockItem->pivot->quantity;

                    if($kurs > $stockItem->latestBalance->usd_kurs) {
                        $itemPrice += (ceil(($stockItem->latestBalance->pre_usd * $kurs) / 50) * 50) * $stockItem->pivot->quantity;
                    } else {
                        $itemPrice += (ceil(($stockItem->latestBalance->pre_usd * $stockItem->latestBalance->usd_kurs) / 50) * 50) * $stockItem->pivot->quantity;
                    }
                }
            }
            
            $item->price = $itemPrice;

            $item->save();
        }  
    }
}
