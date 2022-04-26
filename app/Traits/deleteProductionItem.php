<?php

namespace App\Traits;

use App\Models\ProductionItem;
use App\Models\Reserve;
use App\Models\StockBalance;
use App\Models\StockNeed;

trait deleteProductionItem
{

    public function deleteProductionItem($item)
    {
        $reserve = Reserve::where('production_item_id', $item->id)->first();
        $stockNeed = StockNeed::where('production_item_id', $item->id)->first();

        if($reserve) {
            $stockBalance = StockBalance::find($reserve->stock_balance_id);

            $stockBalance->quantity = $stockBalance->quantity + $reserve->quantity;
            $stockBalance->save();

            $reserve->delete();
        }
        
        if($stockNeed) {
            $stockNeed->delete();
        }

        $item->delete();
    }

}