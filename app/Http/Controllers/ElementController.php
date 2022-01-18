<?php

namespace App\Http\Controllers;

use App\Models\Element;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    public function index()
    {
        return Element::orderBy('price', 'asc')->get();
    }

    public function indexByBox($id)
    {
        return Element::whereRelation('boxes', 'box_id', $id)->get();
    }

    public function element($id)
    {
        return Element::where('id', $id)->first();
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'pre_rub' => 'required|numeric',
            'pre_usd' => 'required|numeric',
            'price' => 'required|numeric',
            'category' => 'required',
            'boxes' => 'required',
        ];

        $this->validate($request, $rules);

        $element = new Element();
        $element->name = $request->name;
        $element->category_id = $request->category;
        $element->pre_rub = $request->pre_rub;
        $element->pre_usd = $request->pre_usd;
        $element->price = $request->price;
        $element->save();
        $element->boxes()->attach($request->boxes, ['element_id' => $element->id]);
    }
}
