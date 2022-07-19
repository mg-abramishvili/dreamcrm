<?php

namespace App\Traits;

use App\Models\Reserve;
use App\Traits\updateStockBalance;

trait createReserveOnly
{
    use updateStockBalance;

    public function createReserveOnly($stockNeed, $balance)
    {
        if($balance->quantity > 0) {
            $reserve = new Reserve();
            $reserve->production_item_id = $stockNeed->production_item_id;
            $reserve->stock_balance_id = $balance->id;
            $reserve->price = $balance->price;
            $reserve->pre_rub = $balance->pre_rub;
            $reserve->pre_usd = $balance->pre_usd;
            
            if($balance->quantity >= $stockNeed->quantity) {
                $reserve->quantity = $stockNeed->quantity;
                $reserve->price_total = $balance->price * $stockNeed->quantity;
                
                $this->updateStockBalance($balance, $stockNeed->quantity);
                
                $reserve->save();

                $stockNeed->delete();
            } else {
                $reserve->quantity = $balance->quantity;
                $reserve->price_total = $balance->price * $balance->quantity;
                
                $this->updateStockBalance($balance, $balance->quantity);

                $reserve->save();

                $stockNeed->quantity = $stockNeed->quantity - $balance->quantity;
                $stockNeed->save();
            }
        }
    }

}