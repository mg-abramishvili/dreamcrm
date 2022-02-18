<?php

namespace App\Http\Controllers;

use App\Models\CalculationDelivery;
use Illuminate\Http\Request;

class CalculationDeliveryController extends Controller
{
    public function index()
    {
        return CalculationDelivery::all();
    }
}
