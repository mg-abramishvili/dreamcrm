<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Calculation;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return ProjectResource::collection(Project::all());
    }

    public function project($id)
    {
        return Project::with('user', 'client', 'calculations.boxes', 'offers', 'production')->find($id);
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
