<?php

namespace App\Http\Controllers;

use App\Models\ProductionItem;
use App\Models\Reserve;
use App\Models\StockBalance;
use App\Models\StockNeed;
use Illuminate\Http\Request;
use App\Traits\deleteProductionItem;

class ProductionItemController extends Controller
{
    use deleteProductionItem;

    public function delete($id)
    {
        $item = ProductionItem::find($id);

        $this->deleteProductionItem($item);
    }
}
