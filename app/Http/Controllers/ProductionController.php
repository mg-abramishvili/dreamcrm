<?php

namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        return Production::with('project')->orderBy('id', 'desc')->get();
    }

    public function production($id)
    {
        return Production::with('project', 'user', 'items.stockItem', 'items.reserves.stockBalance')->find($id);
    }
}
