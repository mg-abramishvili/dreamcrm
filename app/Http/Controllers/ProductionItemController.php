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
        $productionItem = ProductionItem::find($id);

        $reserve = Reserve::where('production_item_id', $productionItem->id)->first();
        $stockNeed = StockNeed::where('production_item_id', $productionItem->id)->first();
        
        $stockBalance = StockBalance::find($reserve->stock_balance_id);

        $quantity = $stockBalance->quantity;
        if($reserve) { $quantity = $quantity + $reserve->quantity; }
        if($stockNeed) { $quantity = $quantity + $stockNeed->quantity; }
        $stockBalance->quantity = $quantity;
        $stockBalance->save();

        if($reserve) {
            $reserve->delete();
        }
        if($stockNeed) {
            $stockNeed->delete();
        }
        $productionItem->delete();
    }
}
