<?php

namespace App\Http\Controllers;

use App\Models\StockCategory;
use Illuminate\Http\Request;

class StockCategoryController extends Controller
{
    public function index()
    {
        return StockCategory::all();
    }

    public function category($id)
    {
        return StockCategory::with('items.balances')->find($id);
    }

    public function store(Request $request)
    {
        $category = new StockCategory();

        $category->name = $request->name;
        $category->slug = $request->slug;

        $category->save();

        return $category->id;
    }
}
