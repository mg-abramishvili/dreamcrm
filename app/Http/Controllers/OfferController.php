<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\Offer;
use App\Models\Project;
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
        return Offer::with('calculations.boxes')->find($id);
    }

    public function store(Request $request)
    {
        $offer = new Offer();

        $offer->project_id = $request->project_id;

        $project = Project::find($request->project_id);

        $offer->save();

        $offer->calculations()->sync($project->calculations);

        $pdf = PDF::loadView('offers.pdf', compact('offer'));
        $pdf->save(public_path() . '/uploads/offers/' . 'kp_' . $offer->id . '.pdf');

        $o = Offer::find($offer->id);
        $o->pdf = '/uploads/offers/' . 'kp_' . $offer->id . '.pdf';
        $o->save();
    }

    public function pdf($id)
    {
        $offer = Offer::with('calculations.boxes', 'calculations.catalogItems')->find($id);

        $pdf = PDF::loadView('offers.pdf', compact('offer'));
        
        // if(public_path() . '/uploads/offers/' . 'kp_' . $offer->id . '.pdf') {
        //     unlink(public_path() . '/uploads/offers/' . 'kp_' . $offer->id . '.pdf');
        // }

        $pdf->save(public_path() . '/uploads/offers/' . 'kp_' . $offer->id . '.pdf');
    }

    // public function offerPDF($id)
    // {
    //     $offer = Offer::with('calculations.catalogItems')->find($id);

    //     $pdf = PDF::loadView('offers.pdf', compact('offer'));
    //     return $pdf->download('offer.pdf');
    // }
}
