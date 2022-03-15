<?php

namespace App\Http\Controllers;

use App\Models\CatalogBox;
use App\Models\CatalogSborka;
use App\Models\Dollar;
use Illuminate\Http\Request;

class CatalogBoxController extends Controller
{
    public function index()
    {
        return CatalogBox::orderBy('name', 'asc')->get();
    }

    public function indexByType($id)
    {
        return CatalogBox::whereRelation('types', 'catalog_type_id', $id)->get();
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
        $box->pre_rub = $request->pre_rub;
        $box->pre_usd = $request->pre_usd;
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

        $box->types()->sync($request->types);
        
        $stockItems = [];
        foreach($request->stock_items as $stockItem) {
            $stockItems[$stockItem['id']] = ['quantity' => $stockItem['quantity']];
        }
        $box->stockItems()->sync($stockItems);
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
        $box->pre_rub = $request->pre_rub;
        $box->pre_usd = $request->pre_usd;
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

        $box->types()->sync($request->types);
        
        $stockItems = [];
        foreach($request->stock_items as $stockItem) {
            $stockItems[$stockItem['id']] = ['quantity' => $stockItem['quantity']];
        }
        $box->stockItems()->sync($stockItems);
    }

    public function delete($id)
    {
        $box = CatalogBox::find($id);

        $box->types()->detach();
        $box->stockItems()->detach();
        
        $box->delete();
    }

    public function updatePrices()
    {
        $kurs = Dollar::find(1)->kurs;

        if(!$kurs) {
            return;
        }

        $boxes = CatalogBox::all();

        foreach ($boxes as $box) {
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
}