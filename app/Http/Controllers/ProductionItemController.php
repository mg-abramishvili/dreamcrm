<?php

namespace App\Http\Controllers;

use App\Models\ProductionItem;
use App\Models\Production;
use App\Models\StockItem;
use App\Models\StockNeed;
use App\Models\Calculation;
use Illuminate\Http\Request;
use App\Traits\createProductionItem;
use App\Traits\changeProductionItem;
use App\Traits\deleteProductionItem;

class ProductionItemController extends Controller
{
    use createProductionItem, changeProductionItem, deleteProductionItem;

    public function replace($id, Request $request)
    {
        $item = ProductionItem::find($id);

        $this->changeProductionItem($item, $request->stock_item, $request->quantity);
    }

    public function delete($id)
    {
        $item = ProductionItem::find($id);

        $this->deleteProductionItem($item);
    }
}
