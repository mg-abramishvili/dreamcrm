<?php

namespace App\Http\Controllers;

use App\Models\CatalogType;
use Illuminate\Http\Request;

class CatalogTypeController extends Controller
{
    public function index()
    {
        return CatalogType::all();
    }

    public function type($id)
    {
        return CatalogType::find($id);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $this->validate($request, $rules);
        
        $type = new CatalogType();

        $type->name = $request->name;

        $type->save();
    }

    public function update($id, Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $this->validate($request, $rules);
        
        $type = CatalogType::find($id);

        $type->name = $request->name;

        $type->save();
    }

    public function delete($id)
    {
        $type = CatalogType::find($id);

        foreach($type->boxes as $box) {
            $box->types()->detach();
            $box->stockItems()->detach();
            $box->delete();
        }
        
        $type->delete();
    }
}
