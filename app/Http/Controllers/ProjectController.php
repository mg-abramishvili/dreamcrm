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
        return ProjectResource::collection(Project::orderBy('created_at', 'desc')->get());
    }

    public function project($id)
    {
        return Project::with('user', 'client', 'endclient', 'calculations.boxes', 'offers', 'production')->find($id);
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

    public function update($id, Request $request)
    {
        $project = Project::find($id);

        if(isset($request->client_id))
        {
            $project->client_id = $request->client_id;
        }

        if(isset($request->end_client_id))
        {
            $project->end_client_id = $request->end_client_id;
        }

        if(isset($request->user_id))
        {
            $project->user_id = $request->user_id;
        }

        if(isset($request->name))
        {
            $project->name = $request->name;
        }
        
        if(isset($request->status))
        {
            $project->status = $request->status;
        }

        if(isset($request->delivery_address))
        {
            $project->delivery_address = $request->delivery_address;
        }

        if(isset($request->end_date))
        {
            $project->end_date = $request->end_date;
        }

        if(isset($request->comment))
        {
            $project->comment = $request->comment;
        }

        $project->save();
    }
}
