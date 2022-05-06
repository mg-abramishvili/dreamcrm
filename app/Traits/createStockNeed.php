<?php

namespace App\Traits;

use App\Models\StockNeed;

trait createStockNeed
{

    public function createStockNeed($quantity, $stockItemID, $productionItem)
    {
        $stockNeed = new StockNeed();
        $stockNeed->quantity = $quantity;
        $stockNeed->stock_item_id = $stockItemID;
        $stockNeed->production_item_id = $productionItem->id;
        $stockNeed->save();
    }

}