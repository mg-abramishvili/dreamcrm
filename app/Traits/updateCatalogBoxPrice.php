<?php

namespace App\Traits;

use App\Models\Dollar;
use App\Models\CatalogBox;
use App\Models\CatalogSborka;

trait updateCatalogBoxPrice
{

    public function updateCatalogBoxPrice($balance)
    {
        $kurs = Dollar::find(1)->kurs;

        if(!$kurs) {
            return;
        }

        foreach($balance->stockItem->catalogBoxes as $catalogBox) {
            $box = CatalogBox::find($catalogBox->id);
            
            $pre_rub = 0;
            $pre_usd = 0;

            foreach($box->stockItems as $stockItem) {
                $pre_rub += $stockItem->balances()->orderBy('created_at', 'desc')->first()->pre_rub * $stockItem->pivot->quantity;
                $pre_usd += $stockItem->balances()->orderBy('created_at', 'desc')->first()->pre_usd * $stockItem->pivot->quantity;
            }

            $box->pre_rub = $pre_rub;
            $box->pre_usd = $pre_usd;

            $rub = $box->pre_rub;
            $usd = $box->pre_usd * $kurs;

            $stockItemsPrice = ceil(($usd + $rub) / 50) * 50;

            $sborkaTarif = CatalogSborka::find(1);
            $sborka = $box->sborka_days * ($box->sborka_persons * $sborkaTarif->person + $sborkaTarif->arenda);
            
            $marzha = $box->marzha;
            
            $box->price = ceil(($stockItemsPrice + $sborka + $marzha) / 50) * 50;

            $box->save();
        }
    }

}