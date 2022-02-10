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
        return StockCategory::with('items')->find($id);
    }
}
