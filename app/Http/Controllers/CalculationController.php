<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index()
    {
        return Calculation::all();
    }

    public function calculation($id)
    {
        return Calculation::with('boxes', 'elements.category')->find($id);
    }

    public function store(Request $request)
    {
        $calculation = new Calculation();
        $calculation->price = $request->price;
        $calculation->quantity = $request->quantity;
        $calculation->production_days = 7;
        $calculation->save();

        $calculation->boxes()->attach($request->box['id'], [
            'pre_rub' => 1,
            'pre_usd' => 2,
            'sborka' => 3,
            'marzha' => 4,
            'price' => 5
        ]);

        foreach($request->elements as $key => $elements)
        {
            foreach($elements as $element)
            {
                $calculation->elements()->attach($element['id'], [
                    'price' => 1,
                    'pre_rub' => 2,
                    'pre_usd' => 3,
                ]);
            }
        }
    }
}
