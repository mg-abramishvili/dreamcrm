<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Calculation;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::with('user', 'calculations.boxes')->orderBy('created_at', 'desc')->get();
    }

    public function project($id)
    {
        return Project::with('user', 'client', 'calculations.boxes', 'offers', 'production')->find($id);
    }

    public function check(Request $request)
    {
        return Project::with('client')->whereRelation('client', 'inn', $request->inn)->get();
    }

    public function store(Request $request)
    {
        $project = new Project();
        $project->user_id = $request->user_id;
        $project->client_id = $request->client_id;
        $project->name = $request->name;
        $project->status = $request->status;
        $project->save();

        $calculation = Calculation::find($request->calculation_id);
        $calculation->project_id = $project->id;
        $calculation->save();

        return $project->id;
    }
}
