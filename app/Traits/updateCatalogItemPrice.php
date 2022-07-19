<?php

namespace App\Traits;

use App\Models\Dollar;
use App\Models\CatalogItem;

trait updateCatalogItemPrice
{

    public function updateCatalogItemPrice($item)
    {
        $kurs = Dollar::find(1)->kurs;

        if(!$kurs) { return; }

        $itemPrice = 0;

        foreach($item->stockItems as $stockItem)
        {
            if($stockItem->latestBalance) {
                $itemPrice += $stockItem->latestBalance->pre_rub * $stockItem->pivot->quantity;

                if($kurs > $stockItem->latestBalance->usd_kurs) {
                    $itemPrice += (ceil(($stockItem->latestBalance->pre_usd * $kurs) / 50) * 50) * $stockItem->pivot->quantity;
                } else {
                    $itemPrice += (ceil(($stockItem->latestBalance->pre_usd * $stockItem->latestBalance->usd_kurs) / 50) * 50) * $stockItem->pivot->quantity;
                }
            }
        }
        
        $item->price = $itemPrice;

        $item->save();
    }

}