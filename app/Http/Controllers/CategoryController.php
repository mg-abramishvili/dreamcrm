<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Element;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('elements')->get();
    }

    public function category($id)
    {
        return Category::where('id', $id)->first();
    }

    public function categoryElements($id)
    {
        return Element::where('category_id', $id)->get();
    }

    public function store(Request $request)
    {
        $category = new Category();

        $category->name = $request->name;
        $category->slug = $request->slug;

        $category->save();
    }
}
