<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index()
    {
        return Calculation::with('user')->orderBy('created_at', 'desc')->get();
    }

    public function calculation($id)
    {
        return Calculation::with('type', 'boxes', 'elements.category', 'delivery', 'user')->find($id);
    }

    public function store(Request $request)
    {
        $calculation = new Calculation();
        $calculation->price = $request->price;
        $calculation->quantity = $request->quantity;
        $calculation->production_days = 30;
        $calculation->type_id = $request->type;
        $calculation->user_id = $request->user;
        $calculation->save();

        $calculation->boxes()->attach($request->box['id'], [
            'pre_rub' => $request->box['pre_rub'],
            'pre_usd' => $request->box['pre_usd'],
            'sborka' => $request->box['sborka'],
            'marzha' => $request->box['marzha'],
            'price' => $request->box['price'],
        ]);

        foreach($request->elements as $key => $elements)
        {
            foreach($elements as $element)
            {
                $calculation->elements()->attach($element['id'], [
                    'price' => $element['price'],
                    'pre_rub' => $element['pre_rub'],
                    'pre_usd' => $element['pre_usd'],
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
