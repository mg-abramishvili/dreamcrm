<?php

namespace App\Http\Controllers;

use App\Models\CatalogSborka;
use Illuminate\Http\Request;

class CatalogSborkaController extends Controller
{
    public function index()
    {
        return CatalogSborka::find(1);
    }
}
