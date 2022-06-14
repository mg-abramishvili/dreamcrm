<?php

namespace App\Http\Controllers;

use App\Models\StockNeed;
use App\Http\Resources\stock\StockNeedsResource;
use Illuminate\Http\Request;

class StockNeedController extends Controller
{
    public function index()
    {
        return StockNeedsResource::collection(StockNeed::all());
    }
}
