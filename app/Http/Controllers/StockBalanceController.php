<?php

namespace App\Http\Controllers;

use App\Models\StockBalance;
use App\Models\StockNeed;
use App\Traits\updateCatalogItemPrice;
use App\Traits\updateCatalogBoxPrice;
use App\Traits\createReserveOnly;
use Illuminate\Http\Request;

class StockBalanceController extends Controller
{
    use updateCatalogItemPrice, updateCatalogBoxPrice, createReserveOnly;

    public function index()
    {
        return StockBalance::with('stockItem')->orderBy('date', 'desc')->get();
    }

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

        $this->updateCatalogItemPrice($balance);

        $this->updateCatalogBoxPrice($balance);

        // check if stockNeeds exists
        $stockNeeds = StockNeed::where('stock_item_id', $balance->stock_item_id)->get();

        if($stockNeeds->count() > 0)
        {
            foreach($stockNeeds as $stockNeed)
            {
                $this->createReserveOnly($stockNeed, $balance);
            }
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

        $this->updateCatalogItemPrice($balance);

        $this->updateCatalogBoxPrice($balance);
    }

    public function delete($id)
    {
        $balance = StockBalance::find($id);

        if(count($balance->reserves))
        {
            return response('Постепление нельзя удалить - оно используется в производстве', 500);
        }

        $tempBalance = $balance;

        $balance->delete();

        $this->updateCatalogItemPrice($tempBalance);

        $this->updateCatalogBoxPrice($tempBalance);
    }
}
