<?php

namespace App\Http\Controllers;

use App\Models\ProductionItem;
use App\Models\Reserve;
use App\Models\StockBalance;
use App\Models\StockNeed;
use Illuminate\Http\Request;

class ProductionItemController extends Controller
{
    public function delete($id)
    {
        $reserve = Reserve::where('production_item_id', $id)->first();
        $stockNeed = StockNeed::where('production_item_id', $id)->first();

        $stockBalance = StockBalance::find($reserve->stock_balance_id);

        if($reserve) {
            $stockBalance->quantity = $stockBalance->quantity + $reserve->quantity;
            $stockBalance->save();

            $reserve->delete();
        }
        
        if($stockNeed) {
            $stockNeed->delete();
        }

        $productionItem->delete();
    }
}
