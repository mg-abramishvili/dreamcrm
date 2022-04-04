<?php

namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        return Production::with('project')->get();
    }

    public function production($id)
    {
        return Production::with('project', 'user')->find($id);
    }
}
