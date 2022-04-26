<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Project;
use App\Models\Calculation;
use App\Models\Reserve;
use App\Models\StockItem;
use App\Models\StockBalance;
use App\Models\StockNeed;
use Illuminate\Http\Request;
use App\Traits\deleteProductionItem;
use App\Traits\updateStockBalance;
use App\Traits\createStockNeed;
use App\Traits\createReserve;
use App\Traits\createProductionItem;
use Carbon\Carbon;

class ProductionController extends Controller
{
    use deleteProductionItem, updateStockBalance, createStockNeed, createReserve, createProductionItem;

    public function index()
    {
        return Production::with('project.calculations.boxes')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function production($id)
    {
        return Production::with([
                'project',
                'user',
                'items.stockItem',
                'items.reserves.stockBalance',
                'items.stockNeeds'
            ])
            ->find($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'project' => 'required',
            'name' => 'required',
        ]);

        $project = Project::with('user')->find($request->project);
        
        $calculation = Calculation::where('project_id', $project->id)->with('boxes.stockItems', 'catalogItems.stockItems')->first();
        
        $production = new Production();
        $production->project_id = $project->id;
        $production->user_id = $project->user->id;
        $production->name = $request->name;
        $production->status = 'new';
        $production->priority = $request->priority;
        $production->ral = $request->ral;
        $production->payment_type = $request->payment_type;
        $production->supply_info = $request->supply_info;
        $production->invoice_number = $request->invoice_number;
        $production->serial_number = $request->serial_number;
        $production->activation_key = $request->activation_key;
        $production->contacts = $request->contacts;
        $production->email = $request->email;
        $production->description = $request->description;
        $production->start_date = $request->start_date;
        $production->end_date = $request->end_date;
        
        $production->save();
        
        foreach($calculation->boxes as $box) {
            foreach($box->stockItems as $stockItem) {
                $productionItem = $this->createProductionItem($production->id, $stockItem->id);

                $quantityNeeds = $stockItem->pivot->quantity * $calculation->quantity;

                $stockBalances = StockBalance::where('stock_item_id', $stockItem->id)->where('quantity', '>', 0)->orderBy('id', 'asc')->get();
                $stockBalancesCount = $stockBalances->count();

                if($stockBalancesCount > 0) {
                    $stockBalance = $stockBalances->first();

                    $this->createReserve($quantityNeeds, $stockItem, $stockBalance, $stockBalances, $production, $productionItem);
                } else {
                    $this->createStockNeed($quantityNeeds, $stockItem, $productionItem);
                }
            }
        }

        foreach($calculation->catalogItems as $catalogItem) {
            if($catalogItem->price > 0) {
                foreach($catalogItem->stockItems as $stockItem) {
                    $productionItem = $this->createProductionItem($production->id, $stockItem->id);

                    $quantityNeeds = $stockItem->pivot->quantity * $calculation->quantity;

                    $stockBalances = StockBalance::where('stock_item_id', $stockItem->id)->where('quantity', '>', 0)->orderBy('id', 'asc')->get();
                    $stockBalancesCount = $stockBalances->count();

                    if($stockBalancesCount > 0) {
                        $stockBalance = $stockBalances->first();

                        $this->createReserve($quantityNeeds, $stockItem, $stockBalance, $stockBalances, $production, $productionItem);
                    } else {
                        $this->createStockNeed($quantityNeeds, $stockItem, $productionItem);
                    }
                }
            }
        }

        return $production->id;
    }

    public function delete($id)
    {
        $production = Production::find($id);

        foreach($production->items as $item) {
            $this->deleteProductionItem($item);
        }

        $production->delete();
    }
}
