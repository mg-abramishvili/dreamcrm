<?php

namespace App\Http\Controllers;

use App\Models\StockNeed;
use App\Http\Resources\stock\StockNeedsResource;
use Illuminate\Http\Request;

class StockNeedController extends Controller
{
    public function index()
    {
        return StockNeedsResource::collection(StockNeed::all());
    }

    public function indexByStockItem($stock_item_id)
    {
        return StockNeedsResource::collection(StockNeed::where('stock_item_id', $stock_item_id)->get());
    }
}
