<?php

namespace App\Http\Controllers;

use App\Models\ProductionItem;
use App\Models\Production;
use App\Models\StockItem;
use App\Models\StockNeed;
use App\Models\Calculation;
use Illuminate\Http\Request;
use App\Traits\deleteProductionItem;
use App\Traits\createProductionItem;

class ProductionItemController extends Controller
{
    use deleteProductionItem, createProductionItem;

    public function replace($id, Request $request)
    {
        $item = ProductionItem::withSum('reserves', 'quantity')->withSum('stockNeeds', 'quantity')->find($id);

        $stockNeeds = StockNeed::where('stock_item_id', $item->stock_item_id)->get();

        $productionID = $item->production_id;
        $stockItemID = $request->stock_item;
        $quantity = $item->reserves_sum_quantity + $item->stock_needs_sum_quantity;

        $this->deleteProductionItem($item);

        $this->createProductionItem($productionID, $stockItemID, $quantity);

        if($stockNeeds->count() > 0)
        {
            return $item->stock_item_id;
        }
    }

    public function delete($id)
    {
        $item = ProductionItem::find($id);

        $this->deleteProductionItem($item);
    }
}
