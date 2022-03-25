<?php

namespace App\Http\Controllers;

use App\Models\StockBalance;
use App\Models\Dollar;
use App\Models\CatalogItem;
use App\Models\CatalogBox;
use App\Models\CatalogSborka;
use Illuminate\Http\Request;

class StockBalanceController extends Controller
{
    public function store($id, Request $request)
    {
        $balance = new StockBalance();

        $balance->stock_item_id = $id;
        $balance->quantity = $request->quantity;
        $balance->pre_rub = $request->pre_rub;
        $balance->pre_usd = $request->pre_usd;
        $balance->price = $request->price;
        $balance->usd_kurs = $request->usd_kurs;
        $balance->date = $request->date;

        $balance->save();

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

    public function update($id, Request $request)
    {
        $balance = StockBalance::find($id);
        
        $balance->quantity = $request->quantity;
        $balance->pre_rub = $request->pre_rub;
        $balance->pre_usd = $request->pre_usd;
        $balance->price = $request->price;
        $balance->usd_kurs = $request->usd_kurs;
        $balance->date = $request->date;

        $balance->save();

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

    public function delete($id)
    {
        $balance = StockBalance::find($id);
        $balance->delete();
    }
}
