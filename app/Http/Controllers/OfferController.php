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
        return Offer::all();
    }

    public function offer($id)
    {
        return Offer::with('calculations')->find($id);
    }

    public function offerPDF($id)
    {
        $offer = Offer::with('calculations.elements')->find($id);

        $pdf = PDF::loadView('offers.pdf', compact('offer'));
        return $pdf->download('offer.pdf');
    }
}
