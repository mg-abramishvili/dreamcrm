<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\Offer;
use App\Models\Project;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\File;

class OfferController extends Controller
{
    public function index()
    {
        return Offer::all();
    }

    public function offer($id)
    {
        return Offer::with('project.calculations.boxes')->find($id);
    }

    public function store(Request $request)
    {
        $offer = new Offer();
        $offer->project_id = $request->project_id;

        $offer->save();

        $this->pdf($offer->id);
    }

    public function pdf($id)
    {
        $offer = Offer::with('project.calculations.boxes', 'project.calculations.catalogItems')->find($id);

        $pdf = PDF::setOptions([
            'tempDir' => storage_path('tmp'),
            'fontDir' => storage_path('fonts'),
            'fontCache' => storage_path('fonts'),
            'chroot' => realpath(base_path()),
            'logOutputFile' => public_path('uploads') . '/' . 'log.htm',
        ])
        ->loadView('offers.pdf', compact('offer'));
        
        $filename = public_path('uploads/offers/') . '/' . 'kp_' . $offer->id . '.pdf';
        
        if(isset($filename)) {
            File::delete($filename);
        }
        
        $pdf->save(public_path('uploads/offers/') . '/' . 'kp_' . $offer->id . '.pdf');

        $offer->pdf = '/uploads/offers/kp_' . $offer->id . '.pdf';
        $offer->save();
    }

    public function update($id, Request $request)
    {
        $offer = Offer::find($id);

        $offer->discount = $request->discount;

        $offer->save();

        $this->pdf($offer->id);
    }
}
