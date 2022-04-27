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
        $reserves = Reserve::where('production_item_id', $item->id)->get();
        $stockNeeds = StockNeed::where('production_item_id', $item->id)->get();

        if($reserves)
        {
            foreach($reserves as $reserve)
            {
                $stockBalance = StockBalance::find($reserve->stock_balance_id);
    
                $stockBalance->quantity = $stockBalance->quantity + $reserve->quantity;
                $stockBalance->save();
    
                $reserve->delete();
            }
        }
        
        if($stockNeeds)
        {
            foreach($stockNeeds as $stockNeed)
            {
                $stockNeed->delete();
            }
        }

        $item->delete();
    }

}