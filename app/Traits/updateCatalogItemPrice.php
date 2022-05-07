<?php

namespace App\Traits;

use App\Models\Dollar;
use App\Models\CatalogItem;

trait updateCatalogItemPrice
{

    public function updateCatalogItemPrice($balance)
    {
        $kurs = Dollar::find(1)->kurs;

        if(!$kurs) {
            return;
        }

        foreach($balance->stockItem->catalogItems as $catalogItem) {
            $item = CatalogItem::find($catalogItem->id);
            
            $pre_rub = 0;
            $pre_usd = 0;

            foreach($item->stockItems as $stockItem) {
                $pre_rub += $stockItem->balances()->orderBy('created_at', 'desc')->first()->pre_rub * $stockItem->pivot->quantity;
                $pre_usd += $stockItem->balances()->orderBy('created_at', 'desc')->first()->pre_usd * $stockItem->pivot->quantity;
            }

            $item->pre_rub = $pre_rub;
            $item->pre_usd = $pre_usd;

            $rub = $item->pre_rub;
            $usd = $item->pre_usd * $kurs;

            $item->price = ceil(($usd + $rub) / 50) * 50;
            
            $item->save();
        }
    }

}