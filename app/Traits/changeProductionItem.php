<?php

namespace App\Traits;

use App\Models\ProductionItem;
use App\Models\Reserve;
use App\Models\StockBalance;
use App\Models\StockNeed;

trait changeProductionItem
{

    public function changeProductionItem($item, $stockItemID, $quantity)
    {
        if(!$item) {
            return;
        }
        
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

        $item->stock_item_id = $stockItemID;
        $item->save();

        $quantityNeeds = $quantity;

        $stockBalances = StockBalance::where('stock_item_id', $stockItemID)->where('quantity', '>', 0)->orderBy('id', 'asc')->get();
        $stockBalancesCount = $stockBalances->count();

        if($stockBalancesCount > 0) {
            $stockBalance = $stockBalances->first();

            $this->createReserve($quantityNeeds, $stockItemID, $stockBalance, $stockBalances, $item);
        } else {
            $this->createStockNeed($quantityNeeds, $stockItemID, $item);
        }
    }

}