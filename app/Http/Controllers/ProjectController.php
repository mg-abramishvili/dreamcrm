<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::all();
    }

    public function project($id)
    {
        return Project::with('user', 'client', 'calculations.boxes', 'offers.calculations.boxes', 'production')->find($id);
    }

    public function check(Request $request)
    {
        $inn = Project::with('client')->whereRelation('client', 'inn', $request->inn)->get();
        $name = Project::with('client')->whereRelation('client', 'name', 'LIKE', "%{$request->name}%")->get();

        return response()->json([
            'inn' => $inn,
            'name' => $name,
        ]);
    }
}
