<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockItemController extends Controller
{
    public function item($id)
    {
        return StockItem::with('balamces')->find($id);
    }

    public function itemsToBuy()
    {
        return StockItem::where('amount', '<', 0)->get();
    }
}
