<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Project;
use App\Models\Calculation;
use App\Models\Reserve;
use App\Models\StockItem;
use App\Models\StockBalance;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        return Production::with('project')->orderBy('id', 'desc')->get();
    }

    public function production($id)
    {
        return Production::with('project', 'user', 'items.stockItem', 'items.reserves.stockBalance')->find($id);
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

                    $this->createReserve($quantityNeeds, $stockBalances, $stockBalance, $productionItem);
                } else {
                    // создаем запись в закупки (нужно купить)
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

    public function createReserve($quantityNeeds, $stockBalances, $stockBalance, $productionItem)
    {
        if($stockBalance->quantity > 0) {
            if($stockBalance->quantity >= $quantityNeeds) {
                $this->updateStockBalance($stockBalance, $quantityNeeds);
            } else {
                $quantityNeeds = $stockBalance->quantity;
                $this->updateStockBalance($stockBalance, $quantityNeeds);
            }
    
            $reserve = new Reserve();
    
            $reserve->production_item_id = $productionItem->id;
            $reserve->stock_balance_id = $stockBalance->id;
            $reserve->quantity = $quantityNeeds;
            $reserve->price = $stockBalance->price;
            $reserve->price_total = $stockBalance->price;
    
            $reserve->save();
        } else {
            $stockBalance = $stockBalances->where('id', '>', $stockBalance->id)->first();
            
            if($stockBalance) {
                $this->createReserve($quantityNeeds, $stockBalances, $stockBalance, $productionItem);
            }
        }
    }

    public function updateStockBalance($stockBalance, $quantity)
    {
        $stockBalance->quantity = $stockBalance->quantity - $quantity;
        
        $stockBalance->save();
    }
}
