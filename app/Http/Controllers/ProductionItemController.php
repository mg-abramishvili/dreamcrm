<?php

namespace App\Http\Controllers;

use App\Models\ProductionItem;
use App\Models\Production;
use App\Models\StockItem;
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

        $productionID = $item->production_id;
        $stockItemID = $request->stock_item;
        $quantity = $item->reserves_sum_quantity + $item->stock_needs_sum_quantity;

        $this->deleteProductionItem($item);

        $this->createProductionItem($productionID, $stockItemID, $quantity);
    }

    public function delete($id)
    {
        $item = Production::find($id);
        
        $this->deleteReserves($item);
        $this->deleteStockNeeds($item);

        $item->delete();
    }
}
