<?php

namespace App\Traits;

trait updateStockBalance
{

    public function updateStockBalance($stockBalance, $quantity)
    {
        $stockBalance->quantity = $stockBalance->quantity - $quantity;
        
        $stockBalance->save();
    }

}