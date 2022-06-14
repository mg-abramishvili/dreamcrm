<?php

namespace App\Http\Controllers;

use App\Models\StockNeed;
use App\Http\Resources\StockNeedsresource;
use Illuminate\Http\Request;

class StockNeedController extends Controller
{
    public function index()
    {
        return StockNeedsresource::collection(StockNeed::all());
    }
}
