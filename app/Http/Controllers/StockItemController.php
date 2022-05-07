<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockItemController extends Controller
{
    public function index()
    {
        return StockItem::with('balances')->orderBy('name', 'asc')->get();
    }

    public function stockNeeds()
    {
        return StockItem::where('needs_quantity', '>', 0)->get();
    }

    public function item($id)
    {
        return StockItem::with('balances.reserves')->find($id);
    }

    public function store(Request $request)
    {
        $item = new StockItem();

        $item->name = $request->name;
        $item->category_id = $request->category_id;
        
        $item->save();
    }

    public function update($id, Request $request)
    {
        $item = StockItem::find($id);

        if(isset($request->name)) {
            $item->name = $request->name;
        }
        if(isset($request->category_id)) {
            $item->category_id = $request->category_id;
        }
        
        $item->save();
    }

    public function delete($id)
    {
        $item = StockItem::find($id);
        
        if(count($item->balances))
        {
            return response('Позицию нельзя удалить - у нее есть остатки', 500);
        }
        if(count($item->productionItems))
        {
            return response('Позицию нельзя удалить - она используется в производстве', 500);
        }
        if(count($item->catalogItems))
        {
            return response('Позицию нельзя удалить - она используется в каталоге', 500);
        }
        if(count($item->catalogBoxes))
        {
            return response('Позицию нельзя удалить - она используется в каталоге', 500);
        }
        
        $item->delete();
    }
}
