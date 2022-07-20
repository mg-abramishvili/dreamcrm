<?php

namespace App\Http\Controllers;

use App\Models\StockNeed;
use App\Http\Resources\stock\StockNeedsResource;
use App\Traits\createReserveFromStockNeed;
use Illuminate\Http\Request;

class StockNeedController extends Controller
{
    use createReserveFromStockNeed;

    public function index()
    {
        return StockNeedsResource::collection(StockNeed::all());
    }

    public function indexByStockItem($stock_item_id)
    {
        return StockNeedsResource::collection(StockNeed::where('stock_item_id', $stock_item_id)->get());
    }

    public function detector(Request $request)
    {
        $needs = $request->needs;

        foreach($needs as $need)
        {
            if($need['quantity'] > 0)
            {
                $stockNeed = StockNeed::find($need['id']);

                $this->createReserveFromStockNeed($stockNeed, $need['quantity']);
            }
        }
    }
}
