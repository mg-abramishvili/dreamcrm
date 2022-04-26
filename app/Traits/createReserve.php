<?php

namespace App\Traits;

use App\Models\Reserve;
use App\Traits\updateStockBalance;
use App\Traits\createStockNeed;

trait createReserve
{
    use updateStockBalance, createStockNeed;

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

}