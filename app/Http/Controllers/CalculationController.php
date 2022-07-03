<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Http\Resources\calculations\CalculationResource;
use App\Http\Resources\calculations\CalculationsResource;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index()
    {
        return CalculationsResource::collection(Calculation::orderBy('created_at', 'desc')->get());
    }

    public function calculation($id)
    {
        return new CalculationResource(Calculation::findOrFail($id));
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

    public function delete($id)
    {
        $calculation = Calculation::find($id);

        if($calculation->project)
        {
            return response('Расчет нельзя удалить - он используется в проекте', 500);
        }

        $calculation->boxes()->detach();
        $calculation->catalogItems()->detach();
        $calculation->delivery()->detach();

        $calculation->delete();
    }
}
