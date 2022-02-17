<?php

namespace App\Http\Controllers;

use App\Models\CatalogDelivery;
use Illuminate\Http\Request;

class CatalogDeliveryController extends Controller
{
    public function index()
    {
        return CatalogDelivery::all();
    }
}
