<?php

namespace App\Http\Controllers;

use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use Illuminate\Http\Request;

class CatalogCategoryController extends Controller
{
    public function index()
    {
        return CatalogCategory::all();
    }

    public function category($id)
    {
        return CatalogCategory::with('items')->find($id);
    }

    public function store(Request $request)
    {
        $category = new CatalogCategory();

        $category->name = $request->name;
        $category->slug = $request->slug;

        $category->save();

        return $category->id;
    }

    public function update($id, Request $request)
    {
        $category = CatalogCategory::find($id);

        if(isset($request->name)) {
            $category->name = $request->name;
        }
        if(isset($request->slug)) {
            $category->slug = $request->slug;
        }

        $category->save();
    }

    public function delete($id)
    {
        $category = CatalogCategory::find($id);
        
        foreach($category->items as $item) {
            $item->delete();
        }
        
        $category->delete();
    }
}
