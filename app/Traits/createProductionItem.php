<?php

namespace App\Traits;

use App\Models\ProductionItem;

trait createProductionItem
{

    public function createProductionItem($productionID, $stockItemID)
    {
        $productionItem = new ProductionItem();
        $productionItem->production_id = $productionID;
        $productionItem->stock_item_id = $stockItemID;
        $productionItem->save();

        return $productionItem;
    }

}