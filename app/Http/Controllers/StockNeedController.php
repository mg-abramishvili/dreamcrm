<?php

namespace App\Http\Controllers;

use App\Models\StockNeed;
use Illuminate\Http\Request;

class StockNeedController extends Controller
{
    public function index()
    {
        return StockNeed::with('productionItem.production', 'stockItem')->get();
    }
}
