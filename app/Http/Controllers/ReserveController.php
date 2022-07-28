<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use App\Http\Resources\ReserveResource;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    public function index()
    {
        return ReserveResource::collection(Reserve::whereRelation('productionItem.production', 'status', '!=', 'waiting_for_feedback')->get());
    }

    public function indexByBalance($stock_balance_id)
    {
        return ReserveResource::collection(Reserve::where('stock_balance_id', $stock_balance_id)->get());
    }
}
