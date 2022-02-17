<?php

namespace App\Http\Controllers;

use App\Models\CatalogType;
use Illuminate\Http\Request;

class CatalogTypeController extends Controller
{
    public function index()
    {
        return CatalogType::all();
    }
}
