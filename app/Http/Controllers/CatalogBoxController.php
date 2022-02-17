<?php

namespace App\Http\Controllers;

use App\Models\CatalogBox;
use Illuminate\Http\Request;

class CatalogBoxController extends Controller
{
    public function index()
    {
        return CatalogBox::all();
    }

    public function indexByType($id)
    {
        return CatalogBox::whereRelation('types', 'type_id', $id)->get();
    }

    public function box($id)
    {
        return CatalogBox::with('types', 'stockItems')->find($id);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'marzha' => 'required|numeric',
            'sborka_days' => 'required|numeric',
            'sborka_persons' => 'required|numeric',
            'sborka' => 'required|numeric',
            'price' => 'required|numeric',
            'types' => 'required',
            'stock_items' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ];

        $this->validate($request, $rules);
        
        $box = new CatalogBox();
        $box->name = $request->name;
        $box->marzha = $request->marzha;
        $box->sborka_days = $request->sborka_days;
        $box->sborka_persons = $request->sborka_persons;
        $box->sborka = $request->sborka;
        $box->price = $request->price;
        $box->length = $request->length;
        $box->width = $request->width;
        $box->height = $request->height;
        $box->weight = $request->weight;
        $box->description = $request->description;
        $box->manager_description = $request->manager_description;
        $box->comment = $request->comment;

        if (!isset($request->gallery)) {
            $request->gallery = [];
        }
        $box->gallery = $request->gallery;

        $box->save();

        $box->types()->attach($request->types, ['catalog_box_id' => $box->id]);
        $box->stockItems()->attach($request->stock_items, ['catalog_box_id' => $box->id]);
    }

    public function update($id, Request $request)
    {
        $rules = [
            'name' => 'required',
            'marzha' => 'required|numeric',
            'sborka_days' => 'required|numeric',
            'sborka_persons' => 'required|numeric',
            'sborka' => 'required|numeric',
            'price' => 'required|numeric',
            'types' => 'required',
            'stock_items' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ];

        $this->validate($request, $rules);
        
        $box = CatalogBox::find($id);
        $box->name = $request->name;
        $box->marzha = $request->marzha;
        $box->sborka_days = $request->sborka_days;
        $box->sborka_persons = $request->sborka_persons;
        $box->sborka = $request->sborka;
        $box->price = $request->price;
        $box->length = $request->length;
        $box->width = $request->width;
        $box->height = $request->height;
        $box->weight = $request->weight;
        $box->description = $request->description;
        $box->manager_description = $request->manager_description;
        $box->comment = $request->comment;

        if (!isset($request->gallery)) {
            $request->gallery = [];
        }
        $box->gallery = $request->gallery;

        $box->save();

        $box->types()->detach();
        $box->types()->attach($request->types, ['catalog_box_id' => $box->id]);
        $box->stockItems()->detach();
        $box->stockItems()->attach($request->stock_items, ['catalog_box_id' => $box->id]);
    }

    public function delete($id)
    {
        $box = CatalogBox::find($id);

        $box->types()->detach();
        $box->stockItems()->detach();
        
        $box->delete();
    }
}