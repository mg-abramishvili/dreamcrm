<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    public function index()
    {
        return Reserve::with('stockBalance.stockItem', 'productionItem.production')->get();
    }
}
