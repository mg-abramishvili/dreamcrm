<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function index()
    {
        return Box::all();
    }

    public function indexByType($id)
    {
        return Box::whereRelation('types', 'type_id', $id)->get();
    }

    public function box($id)
    {
        return Box::with('types')->find($id);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'pre_rub' => 'required|numeric',
            'pre_usd' => 'required|numeric',
            'marzha' => 'required|numeric',
            'sborka' => 'required|numeric',
            'price' => 'required|numeric',
            'types' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ];

        $this->validate($request, $rules);
        
        $box = new Box();
        $box->name = $request->name;
        $box->pre_rub = $request->pre_rub;
        $box->pre_usd = $request->pre_usd;
        $box->marzha = $request->marzha;
        $box->sborka = $request->sborka;
        $box->price = $request->price;
        $box->length = $request->length;
        $box->width = $request->width;
        $box->height = $request->height;
        $box->weight = $request->weight;
        $box->description = $request->description;
        $box->manager_description = $request->manager_description;
        $box->save();
        $box->types()->attach($request->types, ['box_id' => $box->id]);
    }

    public function update($id, Request $request)
    {
        $rules = [
            'name' => 'required',
            'pre_rub' => 'required|numeric',
            'pre_usd' => 'required|numeric',
            'marzha' => 'required|numeric',
            'sborka' => 'required|numeric',
            'price' => 'required|numeric',
            'types' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ];

        $this->validate($request, $rules);
        
        $box = Box::find($id);
        $box->name = $request->name;
        $box->pre_rub = $request->pre_rub;
        $box->pre_usd = $request->pre_usd;
        $box->marzha = $request->marzha;
        $box->sborka = $request->sborka;
        $box->price = $request->price;
        $box->length = $request->length;
        $box->width = $request->width;
        $box->height = $request->height;
        $box->weight = $request->weight;
        $box->description = $request->description;
        $box->manager_description = $request->manager_description;
        $box->save();
        $box->types()->detach();
        $box->types()->attach($request->types, ['box_id' => $box->id]);
    }
}