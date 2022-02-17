<?php

namespace App\Http\Controllers;

use App\Models\StockBalance;
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
    }

    public function delete($id)
    {
        $balance = StockBalance::find($id);
        $balance->delete();
    }
}
