<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockItemController extends Controller
{
    public function item($id)
    {
        return StockItem::with('balances')->find($id);
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
}
