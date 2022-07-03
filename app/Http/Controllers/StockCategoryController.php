<?php

namespace App\Http\Controllers;

use App\Models\StockCategory;
use App\Http\Resources\stock\StockCategoryResource;
use Illuminate\Http\Request;

class StockCategoryController extends Controller
{
    public function index()
    {
        return StockCategory::all();
    }

    public function category($id)
    {
        return new StockCategoryResource(StockCategory::find($id));
    }

    public function store(Request $request)
    {
        $category = new StockCategory();

        $category->name = $request->name;
        $category->slug = $request->slug;

        $category->save();

        return $category->id;
    }

    public function update($id, Request $request)
    {
        $category = StockCategory::find($id);
        
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
        $category = StockCategory::find($id);

        if(count($category->items))
        {
            return response('Раздел нельзя удалить - раздел не пустой', 500);
        }
        
        foreach($category->items as $item) {
            foreach($item->balances as $balance) {
                $balance->delete();
            }
            $item->delete();
        }
        
        $category->delete();
    }
}
