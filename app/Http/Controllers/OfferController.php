<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\Offer;
use Illuminate\Http\Request;
use PDF;

class OfferController extends Controller
{
    public function index()
    {
        $offer = Calculation::orderBy('created_at', 'desc')->first();

        $pdf = PDF::loadView('offers.pdf', compact('offer'));
        return $pdf->download('invoice.pdf');
    }
}
