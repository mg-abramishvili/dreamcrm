<?php

namespace App\Traits;

use App\Models\Reserve;
use App\Models\StockBalance;
use App\Traits\updateStockBalance;

trait createReserveFromStockNeed
{
    use updateStockBalance;

    public function createReserveFromStockNeed($stockNeed, $quantity)
    {
        $stockBalances = StockBalance::where('stock_item_id', $stockNeed->stock_item_id)->get();

        foreach($stockBalances as $stockBalance)
        {
            if($stockBalance->quantity > 0)
            {
                $reserve = new Reserve();
                $reserve->production_item_id = $stockNeed->production_item_id;
                $reserve->stock_balance_id = $stockBalance->id;
                $reserve->price = $stockBalance->price;
                $reserve->pre_rub = $stockBalance->pre_rub;
                $reserve->pre_usd = $stockBalance->pre_usd;

                if($stockBalance->quantity >= $quantity)
                {
                    $reserve->quantity = $quantity;
                    $reserve->price_total = $stockBalance->price * $quantity;
                    
                    $this->updateStockBalance($stockBalance, $quantity);
                    
                    $reserve->save();

                    $stockNeed->quantity = $stockNeed->quantity - $quantity;
                    $stockNeed->save();
                } else {
                    $reserve->quantity = $stockBalance->quantity;
                    $reserve->price_total = $stockBalance->price * $stockBalance->quantity;
                    
                    $this->updateStockBalance($stockBalance, $stockBalance->quantity);
                    
                    $reserve->save();
    
                    $stockNeed->quantity = $stockNeed->quantity - $stockBalance->quantity;
                    $stockNeed->save();
                }

                if($stockNeed->quantity == 0)
                {
                    $stockNeed->delete();
                }
            }
        }
    }

}