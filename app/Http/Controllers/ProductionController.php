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
use Carbon\Carbon;

class ProductionController extends Controller
{
    use deleteProductionItem, updateStockBalance;

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
                $productionItem = new ProductionItem();
                $productionItem->production_id = $production->id;
                $productionItem->stock_item_id = $stockItem->id;
                $productionItem->save();

                $quantityNeeds = $stockItem->pivot->quantity;

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
                    $productionItem = new ProductionItem();
                    $productionItem->production_id = $production->id;
                    $productionItem->stock_item_id = $stockItem->id;
                    $productionItem->save();

                    $quantityNeeds = $stockItem->pivot->quantity;

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

    public function createReserve($quantityNeeds, $stockItem, $stockBalance, $stockBalances, $production, $productionItem)
    {
        if($stockBalance->quantity > 0) {
            $reserve = new Reserve();
            $reserve->production_item_id = $productionItem->id;
            $reserve->stock_balance_id = $stockBalance->id;
            $reserve->price = $stockBalance->price;
            $reserve->pre_rub = $stockBalance->pre_rub;
            $reserve->pre_usd = $stockBalance->pre_usd;
            
            if($stockBalance->quantity >= $quantityNeeds) {
                $reserve->quantity = $quantityNeeds;
                $reserve->price_total = $stockBalance->price * $quantityNeeds;
                
                $quantityNeedsLeft = 0;
                
                $this->updateStockBalance($stockBalance, $quantityNeeds);
            } else {
                $reserve->quantity = $stockBalance->quantity;
                $reserve->price_total = $stockBalance->price * $stockBalance->quantity;
                
                $quantityNeedsLeft = $quantityNeeds - $stockBalance->quantity;
                
                $this->updateStockBalance($stockBalance, $stockBalance->quantity);
            }            
            
            $reserve->save();

            if($quantityNeedsLeft > 0) {
                $stockBalance = $stockBalances->where('id', '>', $stockBalance->id)->first();
                
                if($stockBalance) {
                    $this->createReserve($quantityNeedsLeft, $stockItem, $stockBalance, $stockBalances, $production, $productionItem);
                } else {
                    $this->createStockNeed($quantityNeedsLeft, $stockItem, $productionItem);
                }
            }
        } else {
            $stockBalance = $stockBalances->where('id', '>', $stockBalance->id)->first();
            
            if($stockBalance) {
                $this->createReserve($quantityNeeds, $stockItem, $stockBalance, $stockBalances, $production, $productionItem);
            } else {
                $this->createStockNeed($quantityNeeds, $stockItem, $productionItem);
            }
        }
    }

    public function createStockNeed($quantity, $stockItem, $productionItem)
    {
        $stockNeed = new StockNeed();
        $stockNeed->quantity = $quantity;
        $stockNeed->stock_item_id = $stockItem->id;
        $stockNeed->production_item_id = $productionItem->id;
        $stockNeed->save();
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
