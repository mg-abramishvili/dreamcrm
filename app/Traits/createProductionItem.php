<?php

namespace App\Traits;

use App\Models\ProductionItem;
use App\Models\StockBalance;
use App\Traits\createStockNeed;
use App\Traits\createReserve;

trait createProductionItem
{
    use createStockNeed, createReserve;

    public function createProductionItem($productionID, $stockItemID, $quantity)
    {
        $productionItem = new ProductionItem();
        $productionItem->production_id = $productionID;
        $productionItem->stock_item_id = $stockItemID;
        $productionItem->save();

        $quantityNeeds = $quantity;

        $stockBalances = StockBalance::where('stock_item_id', $stockItemID)->where('quantity', '>', 0)->orderBy('id', 'asc')->get();
        $stockBalancesCount = $stockBalances->count();

        if($stockBalancesCount > 0) {
            $stockBalance = $stockBalances->first();

            $this->createReserve($quantityNeeds, $stockItemID, $stockBalance, $stockBalances, $productionItem);
        } else {
            $this->createStockNeed($quantityNeeds, $stockItemID, $productionItem);
        }
    }
}