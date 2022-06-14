<?php

namespace App\Http\Controllers;

use App\Models\StockNeed;
use App\Http\Resources\StockNeedsResource;
use Illuminate\Http\Request;

class StockNeedController extends Controller
{
    public function index()
    {
        return StockNeedsResource::collection(StockNeed::all());
    }
}
