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

class ProductionController extends Controller
{
    public function index()
    {
        return Production::with('project')->orderBy('id', 'desc')->get();
    }

    public function production($id)
    {
        return Production::with('project', 'user', 'items.stockItem', 'items.reserves.stockBalance', 'items.stockNeeds')->find($id);
    }

    public function store(Request $request)
    {
        $project = Project::with('user')->find($request->project);
        
        $calculation = Calculation::where('project_id', $project->id)->with('boxes.stockItems', 'catalogItems.stockItems')->first();
        
        $production = new Production();
        $production->project_id = $project->id;
        $production->user_id = $project->user->id;
        $production->name = 'Новый проект';
        $production->priority = 'normal';
        $production->status = 'new';
        
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

        // foreach($calculation->catalogItems as $catalogItem) {
        //     if($catalogItem->price > 0) {
        //         foreach($catalogItem->stockItems as $stockItem) {
        //             $productionItem = new ProductionItem();
        //             $productionItem->production_id = $production->id;
        //             $productionItem->stock_item_id = $stockItem->id;
        //             $productionItem->save();
        //         }
        //     }
        // }
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

    public function updateStockBalance($stockBalance, $quantity)
    {
        $stockBalance->quantity = $stockBalance->quantity - $quantity;
        
        $stockBalance->save();
    }

    public function createStockNeed($quantity, $stockItem, $productionItem)
    {
        $stockNeed = new StockNeed();
        $stockNeed->quantity = $quantity;
        $stockNeed->stock_item_id = $stockItem->id;
        $stockNeed->production_item_id = $productionItem->id;
        $stockNeed->save();
    }
}
