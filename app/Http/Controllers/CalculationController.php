<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index()
    {
        return Calculation::with('user', 'boxes', 'project')->orderBy('created_at', 'desc')->get();
    }

    public function calculation($id)
    {
        return Calculation::with('type', 'boxes.stockItems', 'catalogItems.category', 'catalogItems.stockItems', 'delivery', 'user', 'project')->find($id);
    }

    public function store(Request $request)
    {
        $calculation = new Calculation();
        $calculation->pre_rub = $request->pre_rub;
        $calculation->pre_usd = $request->pre_usd;
        $calculation->price = $request->price;
        $calculation->quantity = $request->quantity;
        $calculation->catalog_type_id = $request->type;
        $calculation->user_id = $request->user;
        $calculation->save();

        $calculation->boxes()->attach($request->box['id'], [
            'pre_rub' => $request->box['pre_rub'],
            'pre_usd' => $request->box['pre_usd'],
            'sborka' => $request->box['sborka'],
            'marzha' => $request->box['marzha'],
            'price' => $request->box['price'],
        ]);

        foreach($request->catalog_items as $key => $items)
        {
            foreach($items as $item)
            {
                $calculation->catalogItems()->attach($item['id'], [
                    'price' => $item['price'],
                    'pre_rub' => $item['pre_rub'],
                    'pre_usd' => $item['pre_usd'],
                ]);
            }
        }

        $calculation->delivery()->attach($request->delivery['id'], [
            'direction_from' => $request->delivery['directionFrom'],
            'direction_to' => $request->delivery['directionTo'],
            'days' => $request->delivery['days'],
            'price' =>  $request->delivery['price'],
        ]);
    }
}
