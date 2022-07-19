<?php

namespace App\Traits;

use App\Models\Dollar;
use App\Models\CatalogBox;
use App\Models\CatalogSborka;

trait updateCatalogBoxPrice
{
    public function updateCatalogBoxPrice($box)
    {
        $kurs = Dollar::find(1)->kurs;

        if(!$kurs) { return; }

        $boxPrice = 0;

        foreach($box->stockItems as $stockItem)
        {
            if($stockItem->latestBalance) {
                $boxPrice += $stockItem->latestBalance->pre_rub * $stockItem->pivot->quantity;

                if($kurs > $stockItem->latestBalance->usd_kurs) {
                    $boxPrice += (ceil(($stockItem->latestBalance->pre_usd * $kurs) / 50) * 50) * $stockItem->pivot->quantity;
                } else {
                    $boxPrice += (ceil(($stockItem->latestBalance->pre_usd * $stockItem->latestBalance->usd_kurs) / 50) * 50) * $stockItem->pivot->quantity;
                }
            }
        }

        $sborkaTarif = CatalogSborka::find(1);
        $sborka = $box->sborka_days * ($box->sborka_persons * $sborkaTarif->person + $sborkaTarif->arenda);
        
        $marzha = $box->marzha;
        
        $box->price = $boxPrice + $sborka + $marzha;

        $box->save();
    }
}